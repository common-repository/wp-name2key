<?php
/**
  * Plugin Name: Name2Key Secondlife
  * Plugin URI: http://dazzlesoftware.ca/
  * Description: A plugin that provides a Secondlife Name2key Service. See also: <a href="http://dazzlesoftware.ca" target="_blank">Dazzle Software</a> | <a href="http://dazzlesoftware.ca/forums/" target="_blank">Support Forum</a> | <a href="http://wiki.dazzlesoftware.ca/" target="_blank">Documentation</a>
  * Version: 1.0.3
  * Author: Dazzle Software
  * Author URI: http://dazzlesoftware.ca/
  **/
 
function getPosition($content,$start,$end)
{
	$a1 = strrpos($content,$start);
	$content = substr($content,$a1 + strlen($start));
	while($a2 = strrpos($content,$end))
	{
		$content = substr($content,0,$a2);
	}
	return $content;
}
 
function name2Key($name = '', $uuid = '00000000-0000-0000-0000-000000000000')
{
    $username = explode(' ',$name);
    $search_url = 'http://search.secondlife.com/client_search.php?session='.$uuid.'&q='.$username[0].'+'.$username[1];
	$data = wp_remote_retrieve_body ( wp_remote_get($search_url) );
	$response = getPosition($data,'http://world.secondlife.com/resident/','"');
    if(!preg_match("/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/",$response)) $response = $uuid;
	return $response;
}
	
function get_name2key() {
?>
	    <form name="input" action="<?php echo the_permalink(); ?>" method="post">
            Username: <input type="text" name="username" />
            <input type="submit" value="Lookup" />
        </form>
<?php
	if(isset($_POST['username'])) echo $_POST['username'] . "&nbsp;" . @name2Key($_POST['username']);
}  
add_shortcode('name2key', 'get_name2key');
?>