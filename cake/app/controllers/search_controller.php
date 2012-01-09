<?php
class SearchController extends AppController
{
	var $name = 'Search';

	var $uses = array(
		'User',
		'Group',
		'Project',
	);

	var $components = array(
		'Auth',
		'Security',
		'Session',
		'RequestHandler',
		'Lucene',
	);

	function beforeFilter()
	{
		$this->Auth->allow('opensearch');

		$this->Security->validatePost = false;
		parent::beforeFilter();
	}	

	/** 
	 * Search Page
	 *
	 * @param string $query Search Query
	 */
	function index($query = '')
	{
		if(isset($this->data['Search']['query']))
		{
			$query = $this->data['Search']['query'];
		}
		else if(isset($this->params['form']['query']))
		{
			$query = $this->params['form']['query'];
		}
		else if(isset($this->params['url']['query']))
		{
			$query = $this->params['url']['query'];
		}

		$query = trim($query);

		$limit = 30;
		if(isset($this->params['url']['limit']))
		{
			$limit = $this->params['url']['limit'];
		}

		if(!is_numeric($limit) || $limit < 1)
		{
			$this->cakeError('invalid_field', array('field' => 'Limit'));
			return;
		}

		$start = 0;
		if(isset($this->params['url']['start']))
		{
			$start = $this->params['url']['start'];
		}

		if(!is_numeric($start) || $start < 0)
		{
			$this->cakeError('invalid_field', array('field' => 'Start'));
			return;
		}

		$this->pageTitle = 'Search - "' . $query . '"';
		$this->set('pageName', '"' . $query . '" - Search');

		$this->set('query', $query);

		if(!$this->RequestHandler->prefers('html'))
		{
			$hits = array();

			$total = 0;

			if(!empty($query))
			{
				try {
					$results = $this->Lucene->query($query);
				} catch(Exception $e) {
					$this->cakeError('internal_error', array('action' => 'Perform', 'resource' => 'Search'));
					return;
				}

				foreach($results as $result)
				{
					$idField = $result->model . '_id';
					$id = $result->$idField;

					$entry = array(
						'id' => $id,
						'uniqId' => $result->model . '_' . $id,
						'model' => $result->model,
						'score' => round($result->score, 3),
						'view' => Router::url('/' . low(Inflector::pluralize($result->model)) . '/view/' . $id),
						'date' => date('Y-m-d H:i:s'),
					);

					$tab = $result->model;
					$access = false;

					switch($result->model)
					{
						case 'Discussion':
							$tab = 'Discussions';
							$access = $this->PermissionCmp->check('discussion.view', $result->Discussion_table_type, $result->Discussion_table_id);

							$entry['title'] = $result->Discussion_title;
							$entry['description'] = $result->Discussion_content;
							$entry['view'] = Router::url('/discussions/view/' . $id);
							$entry['date'] = date('Y-m-d H:i:s', strtotime($result->Discussion_created));
							break;
						case 'Doc':
							$tab = 'Documents';
							$access = $this->PermissionCmp->check('doc.view', $result->Doc_table_type, $result->Doc_table_id);

							$entry['title'] = $result->Doc_title;
							$entry['description'] = $result->Doc_description;
							$entry['view'] = Router::url('/docs/view/' . $id);
							$entry['date'] = date('Y-m-d H:i:s', strtotime($result->Doc_created));
							break;
						case 'Group':
							$tab = 'Groups';
							$access = $this->PermissionCmp->check('group.view', 'group', $id);

							$entry['title'] = $result->Group_name;
							$entry['description'] = $result->Group_description;
							$entry['view'] = Router::url('/groups/dashboard/' . $id);
							$entry['date'] = date('Y-m-d H:i:s', strtotime($result->Group_created));
							break;
						case 'Inbox':
							$tab = 'Inbox Messages';
							if($result->Inbox_sender_id == $this->Session->read('Auth.User.id'))
							{
								$access = true;
							}

							if($result->Inbox_receiver_type == 'user' && $result->Inbox_receiver_id == $this->Session->read('Auth.User.id'))
							{
								$access = true;
							}

							$entry['title'] = $result->Message_subject;
							$entry['description'] = $result->Message_body;
							$entry['view'] = Router::url('/inbox/view/' . $id);
							$entry['date'] = date('Y-m-d H:i:s', strtotime($result->Message_date));
							break;
						case 'Project':
							$tab = 'Projects';
							$access = $this->PermissionCmp->check('project.view', 'project', $id);

							$entry['title'] = $result->Project_name;
							$entry['description'] = $result->Project_description;
							$entry['view'] = Router::url('/projects/dashboard/' . $id);
							$entry['date'] = date('Y-m-d H:i:s', strtotime($result->Project_created));
							break;
						case 'User':
							$tab = 'Users';

							//TODO: Fix this for permissions
							$access = true;

							$entry['title'] = $result->User_name;
							$entry['description'] = $result->User_description;
							$entry['view'] = Router::url('/users/dashboard/' . $id);
							$entry['date'] = date('Y-m-d H:i:s', strtotime($result->User_registered));
							break;
						case 'Note':
							$tab = 'Notes';
							$access = $this->PermissionCmp->check('note.view', $result->Note_table_type, $result->Note_table_id);

							$entry['title'] = $result->Note_title;
							$entry['description'] = $result->Note_content;
							$entry['view'] = Router::url('/notes/view/' . $result->Note_id);
							$entry['date'] = date('Y-m-d H:i:s', strtotime($result->Note_created));
							break;
					}

					if($access)
					{
						if($this->RequestHandler->prefers('extjs'))
						{
							$hits[$tab][] = array_values($entry);
						}
						else
						{
							$hits[$tab][] = $entry;
						}

						$total++;
					}
				}
			}

			/* TODO : Make this slice the entire array, not by each model */
			foreach($hits as $model => $results)
			{
				$hits[$model] = array_slice($results, $start, $limit);
			}

			$page = ($start - ($start % $limit)) / $limit;
			$pages = ($total - ($total % $limit)) / $limit;

			$previous = 0;
			if($page > 0)
			{
				$previous = ($page - 1) * $limit;
			}

			$next = 0;
			if($page < $pages)
			{
				$next = ($page + 1) * $limit;
			}

			$last = ($pages - 1) * $limit;

			$response = array(
				'success' => true,
				'results' => $hits,
				'previous' => $previous,
				'next' => $next,
				'last' => $last,
				'query' => $query,
				'total' => $total,
				'start' => $start,
				'limit' => $limit,
			);

			$this->set('response', $response);
		}
	}

	/**
	 * Generate OpenSearch XML Document
	 */
	function opensearch()
	{
		if(!$this->RequestHandler->prefers('xml'))
		{
			$this->cakeError('error404');
			return;
		}
	}

	/**
	 * Help for Index
	 */
	function help_index() 
	{
		$this->pageTitle = 'Help - Index - Search';
		$this->set('pageName', 'Search - Index - Help');
	}
}
?>
