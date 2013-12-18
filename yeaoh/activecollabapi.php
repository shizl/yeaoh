<?php

  require_once 'ActiveCollab/autoload.php';

  use \ActiveCollab\Client as API;
  use \ActiveCollab\Connectors\Curl as CurlConnector;

  API::setUrl('http://project.yeaoh.com/public/api.php');
  API::setKey('43-TTs4rYBXBrxZOjKwtddWk8MyE2TfRggz1zsgnT5O');
  API::setConnector(new CurlConnector);

  print '<pre>';

  print "API info:\n\n";

  var_dump(API::info());

  print "Task creation example:\n\n";

  var_dump(API::call('projects/65/tasks/add', null, array(
    'task[name]' => 'This is a task name',
    'task[assignee_id]' => 48,
    'task[other_assignees]' => array(3, 1),
  ), array(
    '/Library/WebServer/Documents/BZHI6GtCQAEEMz-.jpg-large.jpeg'
  )));

  print '</pre>';
