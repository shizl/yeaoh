<?php
function yeaohpm_settings($form,$form_state) {
  $form['yeaohpm_setting'] = array(
      '#type'=>'textfield',
      '#title' =>'token',
      '#default_value'=>variable_get('yeaohpm_setting'),
      );
  $form['yeaohpm_api_url'] = array(
      '#type'=>'textfield',
      '#title' =>'API Url',
      '#default_value'=>variable_get('yeaohpm_api_url'),
      );
  $form['yeaohpm_request_thanks'] = array(
      '#type'=>'textfield',
      '#title' =>'Thanks page',
      '#default_value'=>variable_get('yeaohpm_request_thanks'),
      );
  return system_settings_form($form);
}

