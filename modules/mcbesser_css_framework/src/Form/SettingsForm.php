<?php
   namespace Drupal\mcbesser_css_framework\Form;
   
   use Drupal\Core\Form\ConfigFormBase as cf;
   use Drupal\Core\Form\FormStateInterface as fsi;
   
   use Drupal\Core\Render\Markup;
    
   class SettingsForm extends cf
      {
         const SETTINGS = 'mcbesser_css_framework.settings';  
         const LIBRARIES_PATH = '/libraries/McBesser/CSS-Framework/css/';
         const MASK_CSS = '/.*\.css$/';
         
         private $libraries_path = NULL;
         
         public function __construct()
            {
               
            } 
            
         public function getFormId()
            {
               return 'mcbesser_css_framework_settings';
            }
            
         protected function getEditableConfigNames()
            {
               return [static::SETTINGS,];
            }
            
         public function buildForm(array $form, fsi $form_state)
            {
               // load settings
               $config['settings'] = $this->config(static::SETTINGS);
               /* ------------------------------------------------------- */
               // set vertical tabs
               $form['advanced'] = [
                                    '#type' => 'vertical_tabs',
                                    '#title' => $this->t('Settings'),
                                    '#title_display' => 'invisible',
                                   ];
               $form['paths'] = [
                                   '#type' => 'details',
                                   '#title' => $this->t('Pfade',[],['langcode' => 'de']),
                                   '#group' => 'advanced',
                                 ];
               /* -------------------------------------------------------------- */
               /* Paths */
               $form['paths']['libraries_path'] = [
                     '#type' => 'textfield',
                     '#title' => $this->t('Pfad der Bibliothek',[],['langcode' => 'de']),
                     '#default_value' => ($config['settings']->get('libraries_path'))?$config['settings']->get('libraries_path'):static::LIBRARIES_PATH,
                     #'#description' => $this->t('',[],['langcode' => 'de']),
                  ];
               /* -------------------------------------------------------------- */
               /* init Files */
               # $banks = file_scan_directory(drupal_get_path('module', 'order').'/images/gateways', '/^.*\png$/');
               # '/.*\.png$/'
               # file_scan_directory($dir, $mask, $options = array(), $depth = 0)
               // (de) ID der Freiwilligenagentur
               $this->test();
               /* -------------------------------------------------------------- */                       
               
               return parent::buildForm($form, $form_state);
            }
            
         public function submitForm(array &$form, FormStateInterface $form_state) 
            {
               /* prevent lost in space data */
               $this->configFactory->getEditable(static::SETTINGS)->delete();
               /* write submit data */                     
               $this->configFactory->getEditable(static::SETTINGS)
                     // Paths
                     ->set('libraries_path', ($form_state->getValue('agencyId'))?$form_state->getValue('agencyId'):static::LIBRARIES_PATH)
                     // save
                     ->save();
               
               parent::submitForm($form, $form_state);
            }
         /* ########################################################################################### */
         private function test()
            {
               $settings = \Drupal::config('mcbesser_css_framework.settings');
               $this->dump($settings->get('js_code'));
            }
         /* ########################################################################################### */
         // custom functions
            
         /* DEV */
         private function dump($mixed, $setting='')
            {
               print '<pre>';
                  switch($setting)
                     {
                        case 'key':             
                        case 'keys':             
                        case 'array':             
                           var_dump(array_keys($mixed));
                        break;
                        case 'object':             
                        case 'obj':             
                        case 'method':             
                           var_dump(get_class_methods($mixed));
                        break;          
                        case 'msg':  
                           ob_start();
                           var_dump($mixed);
                           $result = ob_get_clean();                   
                           \Drupal::messenger()->addMessage($result, 'warning');
                        break;
                        default:                        
                           var_dump($mixed);
                        break;
                     }
               print '</pre>';
            }
      }