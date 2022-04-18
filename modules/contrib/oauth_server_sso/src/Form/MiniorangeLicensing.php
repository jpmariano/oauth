<?php
/**
 * @file
 * Contains Licensing information for miniOrange OAuth Server Login Module.
 */

 /**
 * Showing Licensing form info.
 */
namespace Drupal\oauth_server_sso\Form;
use Drupal\Core\Form\FormBase;
use Drupal\oauth_server_sso\MiniorangeOAUthServerSupport;

class MiniorangeLicensing extends FormBase {

    public function getFormId() {
        return 'oauth_server_sso_licensing';
    }

    public function buildForm(array $form, \Drupal\Core\Form\FormStateInterface $form_state)
    {

        $form['markup_library'] = array(
            '#attached' => array(
                'library' => array(
                    "oauth_server_sso/oauth_server_sso.style_settings",
                )
            ),
        );

        $form['mo_oauth_server_style'] = array('#markup' => '<div class="mo_oauth_table_layout_1"><div class="mo_oauth_table_layout">');

        $form['markup_1'] = array(
            '#markup' =>t('<br><h3>Upgrade Plans</h3><hr><br><div class="mo_oauth_server_highlight_background_note_1">If you wish to test the features that we provide in the Premium version of our module, please reach out to us at <a href="mailto:drupalsupport@xecurify.com">drupalsupport@xecurify.com</a></div>')
        );

        $form['markup_free'] = array(
            '#markup' => '<html lang="en">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <!-- Main Style -->
        </head>
        <body>
        <!-- Pricing Table Section -->
        <section id="pricing-table">
            <div class="container_1">
                <div class="row">
                    <div class="pricing">
                        <div>
                            <div class="pricing-table class_inline_1">
                                <div class="pricing-header">
                                    <h2 class="pricing-title">Features / Plans</h2>
                                </div>
                                <div class="pricing-list">
                                    <ul>
                                        <li>Client support</li>
                                        <li>Authorization Code Grant</li>
                                        <li>Resource Owner Credentials Grant (Password Grant)</li>
                                        <li>Client Credentials Grant</li>
                                        <li>Implicit Grant</li>
                                        <li>Refresh token Grant</li>
                                        <li>Enable/Disable Switch</li>
                                        <li>Block Unauthenticated Requests to the REST API</li>
                                        <li>Token Length</li>
                                        <li>Redirect URI validation</li>
                                        <li>Enforce State parameter</li>
                                        <li>OIDC support</li>
                                        <li>Extended OAuth API support</li>
                                        <li>JWT Signing Algorithm</li>
                                        <li>Error Logging</li>
                                        <li>Multi-site Supporte</li>
                                        <li>Login Reports</li>
                                        <li>End to End Integration **</li>
                                        <li>Support</li>
                                    </ul>
                                </div>
                            </div>    
                            <div class="pricing-table class_inline">
                                <div class="pricing-header">
                                <p class="pricing-title">Free</p>
                                <p class="pricing-rate"> <br><br></p>
                                <div class="filler-class"></div>
                                    <a class="btn btn-primary">Currently Active</a>
                                    </div>
                            <div class="pricing-list">
                                <ul>
                                <li>1</li>
                                <li>&#x2714;</li>
                                <li> </li>
                                <li> </li>
                                <li> </li>
                                <li> </li>
                                <li> </li>
                                <li> </li>
                                <li> </li>
                                <li> </li>
                                <li> </li>
                                <li></li>
                                <li></li>
                                <li> </li>
                                <li> </li>
                                <li> </li>
                                <li> </li>
                                <li> </li>
                                <li>Basic Support by Email</li>
                                </ul>
                            </div>
                        </div>
                        
                       
                       
                        <div class="pricing-table class_inline">
                            <div class="pricing-header">
                                <p class="pricing-title-premium">Premium</p>
                                <p class="mo_oauth_text_color_white">Number of Users(Unique users that will be using SSO annually) : </p>
                                    <p class="pricing-rate mo_auth_margin_top"></p>
                                 <a href="https://www.miniorange.com/contact" target="_blank" class="btn btn-primary">Contact Us</a>
                            </div>

                            <div class="pricing-list">
                                <ul>
                                    <li>Unlimited</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>
                                    <li>&#x2714;</li>                                    
                                    <li>&#x2714;</li>                                    
                                    <li>&#x2714;</li>                                    
                                    <li>&#x2714;</li>     
                                    <li>Premium Support Plans</li>                            
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Pricing Table Section End -->
    </body>
    </html>',
        );


      $form['mo_oauth_server_import_ajax'] = [ '#type' => 'html_tag', '#tag' => 'script', '#attributes'=> ["src"=>"https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"]];

      $form['mo_oauth_server_user_based_pricing'] = [ '#type' => 'html_tag', '#tag' => 'script', '#value' => $this ->t('
              jQuery(document).ready(function($){
                  $($("p.pricing-rate")[1]).append($(".js-form-item-do-it-yourself-pricing"));
              });
          '), ];


      $form['Do_it_yourself_pricing'] = array(
        '#type' => 'select',
        '#options' => array(
          '1 - 100' => t('1 - 100 : $450'),
          '101 - 200' => t('101 - 200 : $550'),
          '201 - 300' => t('201 - 300 : $650'),
          '301 - 400' => t('301 - 400 : $750'),
          '401 - 500' => t('401 - 500 : $850'),
          '501 - 1000' => t('501 - 1000 : $1250'),
          '1001 - 2000' => t('1001 - 2000 : $1600'),
          '2001 - 3000' => t('2001 - 3000 : $1900'),
          '3001 - 4000' => t('3001 - 4000 : $2150'),
          '4001 - 5000' => t('4001 - 5000 : $2400'),
          '5000+' => t('5000+ Users - Contact Us'),
        ),
        '#attributes' => array('class'=> array('mo_oauth_server_user_based_pricing')),
      );

      $form['markup_6'] = array(
        '#markup' => '<h3>** End to End OAuth Server Integration (additional charges applied)</h3>'
          . ' We will setup a Conference Call / Gotomeeting and do end to end configuration for you to setup dupal as an OAuth/OIDC Provider.'
          . ' We provide services to do the configuration on your behalf.<br />'
          . ' If you have any doubts regarding the licensing plans, you can mail us at <a href="mailto:drupalsupport@xecurify.com"><i>drupalsupport@xecurify.com</i></a> or submit a query using the support form.<br><br>
                    <br><b>10 Days Return Policy -</b><br><br> At miniOrange, we want to ensure you are 100% happy with your purchase. If the premium module you purchased is not working as advertised and you have attempted to resolve any issues with our support team, which could not get resolved. We will refund the whole amount given that you have a raised a refund request within the first 10 days of the purchase. Please email us at <a href="mailto:drupalsupport@xecurify.com">drupalsupport@xecurify.com</a> for any queries regarding the return policy. <br><br></div></div></div></div>'
      );

        return $form;
    }

    public function submitForm(array &$form, \Drupal\Core\Form\FormStateInterface $form_state) {

    }
}

