<?php
namespace Drupal\oauth_server_sso;

class mo_oauth_server_visualTour {

    public static function genArray($overAllTour = 'tabTour'){
        $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $exploded = explode('/', $link);
        $getPageName = end($exploded);
        $Tour_Token = \Drupal::config('oauth_server_sso.settings')->get('mo_saml_tourTaken_' . $getPageName);
        if($overAllTour == 'overAllTour'){
            $getPageName = 'overAllTour';
        }
        $moTourArr = array (
            'pageID' => $getPageName,
            'tourData' => mo_oauth_server_visualTour::getTourData($getPageName),
            'tourTaken' => $Tour_Token,
            'addID' => mo_oauth_server_visualTour::addID(),
            'pageURL' => $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
        );

        \Drupal::configFactory()->getEditable('oauth_server_sso.settings')->set('mo_saml_tourTaken_' . $getPageName, TRUE)->save();
        $moTour = json_encode($moTourArr);
        return $moTour;
    }

    public static function addID()
    {
        $idArray = array(
            array(
                'selector'  =>'li.tabs__tab:nth-of-type(1)',
                'newID'     =>'mo_vt_oauth_server_config',
            ),
            array(
                'selector'  =>'li.tabs__tab:nth-of-type(2)',
                'newID'     =>'mo_vt_oauth_server_general',
            ),
            array(
                'selector'  =>'li.tabs__tab:nth-of-type(5)',
                'newID'     =>'mo_vt_oauth_server_upgrade',
            ),
            array(
                'selector'  =>'li.tabs__tab:nth-of-type(6)',
                'newID'     =>'mo_vt_oauth_server_support',
            ),
            array(
                'selector'  =>'li.tabs__tab:nth-of-type(7)',
                'newID'     =>'mo_vt_oauth_server_account',
            ),
        );
        return $idArray;
    }
    public static function getTourData($pageID)
    {

        $tourData = array();
        $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $exploded = explode('/', $link);
        $getPageName = end($exploded);
        $Tour_Token = \Drupal::config('oauth_server_sso.settings')->get('mo_saml_tourTaken_' . $getPageName);
        $stat = \Drupal::config('oauth_server_sso.settings')->get('oauth_server_sso_add_client_status');

        if($Tour_Token == FALSE) {
            $tourData['config_client'] = array(
                0 => array(
                    'targetE'       => 'mo_vt_oauth_server_config',
                    'pointToSide'   => 'left',
                    'titleHTML'     => '<h1>Configure OAuth Client</h1>',
                    'contentHTML'   => 'Configure your OAuth Client with OAuth Server here to perform SSO.',
                    'ifNext'        => true,
                    'buttonText'    => 'Next',
                    'cardSize'      => 'largemedium',
                ),
                1 => array(
                    'targetE'       => 'mo_vt_oauth_server_general',
                    'pointToSide'   => 'left',
                    'titleHTML'     => '<h1>General Settings</h1>',
                    'contentHTML'   => '<p>You can configure Token Length and Token Expiry time here.</p>',
                    'ifNext'        => true,
                    'buttonText'    => 'Next',
                    'cardSize'      => 'largemedium',
                ),
                2 => array(
                    'targetE'       => 'mo_vt_oauth_server_upgrade',
                    'pointToSide'   => 'left',
                    'titleHTML'     => '<h1>Upgrade here</h1>',
                    'contentHTML'   => 'You can see the complete list of features that we provide in our various plans and can also upgrade to any of them.',
                    'ifNext'        => true,
                    'buttonText'    => 'Next',
                    'cardSize'      => 'largemedium',
                ),
                3 => array(
                    'targetE'       => 'mo_vt_oauth_server_support',
                    'pointToSide'   => 'left',
                    'titleHTML'     =>  '<h1>Need Help?</h1>',
                    'contentHTML'   =>  'Get in touch with us and we will help you setup the module in no time.',
                    'ifNext'        => true,
                    'buttonText'    => 'Next',
                    'cardSize'      => 'largemedium',
                ),
                4 => array(
                    'targetE'       => 'mo_vt_oauth_server_account',
                    'pointToSide'   => 'left',
                    'titleHTML'     =>  '<h1>Register/Login</h1>',
                    'contentHTML'   =>  'You can Register/Login here with miniOrange.',
                    'ifNext'        => true,
                    'buttonText'    => 'End Tour',
                    'cardSize'      => 'largemedium',
                ),
                5 => array(
                    'targetE'       => 'firstname',
                    'pointToSide'   => 'left',
                    'titleHTML'     => '<h1>Client Name</h1>',
                    'contentHTML'   => 'Please Enter your OAuth Client name to configure.',
                    'ifNext'        => true,
                    'buttonText'    => 'Next',
                    'cardSize'      => 'largemedium',
                ),
                6 => array(
                    'targetE'       => 'redirecturl',
                    'pointToSide'   => 'left',
                    'titleHTML'     => '<h1>Authorized Redirect URL</h1>',
                    'contentHTML'   => 'Enter <b>Authorized Redirect URL</b> from OAuth Client.',
                    'ifNext'        => true,
                    'buttonText'    => 'Next',
                    'cardSize'      => 'largemedium',
                ),
                7 => array(
                    'targetE'       => 'button_config_center',
                    'pointToSide'   => 'left',
                    'titleHTML'     => '<h1>Next</h1>',
                    'contentHTML'   => 'Click on Next button to Save your configurations.',
                    'ifNext'        => true,
                    'buttonText'    => 'Next',
                    'cardSize'      => 'medium',
                ),
                8 => array(
                    'targetE'       => 'mo_oauth_server_support_query',
                    'pointToSide'   => 'right',
                    'titleHTML'     => '<h1>Need Help?</h1>',
                    'contentHTML'   => 'Get in touch with us and we will help you setup the module in no time.',
                    'ifNext'        => true,
                    'buttonText'    => 'End Tour',
                    'cardSize'      => 'medium',
                    'ifskip'        =>  'hidden',
                ),
            );
        }
        else{
            $tourData['config_client'] = array(
                0 => array(
                    'targetE'       => 'firstname',
                    'pointToSide'   => 'left',
                    'titleHTML'     => '<h1>Client Name</h1>',
                    'contentHTML'   => 'Please Enter your OAuth Client name to configure.',
                    'ifNext'        => true,
                    'buttonText'    => 'Next',
                    'cardSize'      => 'largemedium',
                ),
                1 => array(
                    'targetE'       => 'redirecturl',
                    'pointToSide'   => 'left',
                    'titleHTML'     => '<h1>Authorized Redirect URL</h1>',
                    'contentHTML'   => 'Enter <b>Authorized Redirect URL</b> from OAuth Client.',
                    'ifNext'        => true,
                    'buttonText'    => 'Next',
                    'cardSize'      => 'largemedium',
                ),
                2 => array(
                    'targetE'       => 'button_config_center',
                    'pointToSide'   => 'left',
                    'titleHTML'     => '<h1>Next</h1>',
                    'contentHTML'   => 'Click on Next button to Save your configurations.',
                    'ifNext'        => true,
                    'buttonText'    => 'Next',
                    'cardSize'      => 'medium',
                ),
                3 => array(
                    'targetE'       => 'mo_oauth_server_support_query',
                    'pointToSide'   => 'right',
                    'titleHTML'     => '<h1>Confiugre OAuth Server</h1>',
                    'contentHTML'   => 'Enter details to configure your OAuth Server.',
                    'ifNext'        => true,
                    'buttonText'    => 'End Tour',
                    'cardSize'      => 'medium',
                    'ifskip'        =>  'hidden',
                ),
            );
        }

        if($stat != '' || $stat == 'review') {
            $tourData['config_client'] = array(
                0 => array(
                    'targetE' => 'clientID',
                    'pointToSide' => 'left',
                    'titleHTML' => '<h1>Client ID</h1>',
                    'contentHTML' => 'Provide this Client ID to your OAuth Client to Configure with OAuth Server.',
                    'ifNext' => true,
                    'buttonText' => 'Next',
                    'img' => array(),
                    'cardSize' => 'largemedium',
                    'action' => '',
                ),
                1 => array(
                    'targetE' => 'clientSecret',
                    'pointToSide' => 'left',
                    'titleHTML' => '<h1>Client Secret</h1>',
                    'contentHTML' => 'Provide this Client Secret to your OAuth Client to Configure with OAuth Server.',
                    'ifNext' => true,
                    'buttonText' => 'Next',
                    'img' => array(),
                    'cardSize' => 'largemedium',
                    'action' => '',
                ),
                2 => array(
                    'targetE' => 'button_config_vt',
                    'pointToSide' => 'left',
                    'titleHTML' => '<h1>Delete Client</h1>',
                    'contentHTML' => 'Click here for Delete your OAuth Server Configuration.',
                    'ifNext' => true,
                    'buttonText' => 'Next',
                    'img' => array(),
                    'cardSize' => 'largemedium',
                    'action' => '',
                ),
                3 => array(
                    'targetE' => 'button_config_update_vt',
                    'pointToSide' => 'left',
                    'titleHTML' => '<h1>Update</h1>',
                    'contentHTML' => 'Click here for Update your configuration settings.',
                    'ifNext' => true,
                    'buttonText' => 'Next',
                    'img' => array(),
                    'cardSize' => 'largemedium',
                    'action' => '',
                ),
                4 => array(
                    'targetE' => 'endpointsUrl',
                    'pointToSide' => 'left',
                    'titleHTML' => '<h1>How to Setup?</h1>',
                    'contentHTML' => 'These are the endpoints and scopes you need to provide your client so that you can use your Drupal Site for Single Sign-On!.',
                    'ifNext' => true,
                    'buttonText' => 'Next',
                    'img' => array(),
                    'cardSize' => 'big',
                    'action' => '',
                ),
                5 => array(
                    'targetE'       => 'mo_oauth_server_support_query',
                    'pointToSide'   => 'right',
                    'titleHTML'     => '<h1>Need Help?</h1>',
                    'contentHTML'   => 'Get in touch with us and we will help you setup the module in no time.',
                    'ifNext'        => true,
                    'buttonText'    => 'End Tour',
                    'cardSize'      => 'medium',
                    'ifskip'        =>  'hidden',
                ),

            );
        }
        return $tourData[$pageID] ;
    }
}
