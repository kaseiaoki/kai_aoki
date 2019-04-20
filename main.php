<?php
$username = $_POST['id'];

$github =  new github_contributions();
$result = $github->get_github_contributions( $username );
echo json_encode($result);
/**
 * github_contributions
 *
 * @access public
 * @param string url
 * @return
 * @see
 */

class github_contributions{
    public function get_github_contributions( $username ) {
        try{
            $data = http_get( "https://github.com/users/".$username."/contributions" );
            $results_array = array_slice( $this->parse_contributions( $data ) , -28); // respect GA
            return $results_array;
        }catch ( Exception $e ) {
            echo $e->getMessage();
        }
    }

    public function parse_contributions( $data ) {
        $today = date( 'Y-m-d' );
        $html_lines = str_replace( array( "\r\n","\r","\n" ), "\n", $data );
        $lines = explode( "\n", $html_lines );
        $date_data = array();
        foreach ( $lines as $line ) {
            if( preg_match("/data-date=\"(.*)\"/", $line, $matches ) ){
                $date = $matches[1] ;
                if( preg_match("/data-count=\"([0-9]+)\"/", $line, $matches ) ) {
                    $data = intval( $matches[1] );
                    $date_data += array( $date => $data );
                }
            }
        }
        return $date_data;
    }
}

/**
 * http_Get
 *
 * @access public
 * @param string url
 * @return
 * @see
 */
function http_get($url){
    $option = [
        CURLOPT_RETURNTRANSFER => true,
    ];
    $curl = curl_init( $url );
    curl_setopt_array( $curl, $option );
    $data = curl_exec($curl);
    $info = curl_getinfo($curl);
    if ( $info['http_code'] !== 200 ) {
        return false;
    } else {
        return $data;
    }
}