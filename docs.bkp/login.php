<?php
require_once("db_user.php");
require_once("user_logic.php");
require_once("LDAPw3.php");

$email = $_POST["email"];
$password = $_POST["password"];

if ($_POST['email']){
        if (empty($_POST['email']) || empty($_POST['password'])) {
                echo "error empty<br>";
        } elseif ($ds = @ldap_connect('bluepages.ibm.com')) {
                echo "success connection";
                @ldap_bind($ds);
                $data = @ldap_search($ds, 'ou=bluepages,o=ibm.com', '(&(uid=*)(c=*)(mail='.$_POST['email'].'))');
                $info = @ldap_get_entries($ds, $data);

                echo "$data<br>";
                echo "$info<br>";

                if($info['count']){
                        $employee = ldap_result_format($info[0]);
                        if (@ldap_bind($ds, $employee['dn'], $_POST['password'])) {
                                $user = getUser($db, $employee['mail']);
                                if($user == null) {
                                    $_SESSION["danger"] = "User not registered!";
                                            header("Location: login_page.php");
                                            } else {
                                                $_SESSION["success"] = "User successfully logged in!";
						$_SESSION["name"] = $employee['cn'];	
                                              if($user['groups'] == "HC") { $_SESSION['hcgroup'] = true; }
						if(in_array($user['bucket'], array('L1','L2','L3','L4','JA','ETG'), true)) {
                                                  $level = "one";
                                                            }elseif($user['bucket'] == "24x7"){
                                                                $level = "two";
                                                                }elseif($user['bucket'] == "DISPATCHER"){
                                                                    $level = "three";
                                                                        }else{
                                                                            $level = "four";
                                                                                    }
                                                                                 logUser($user['email'], $user['groups']);
                                                                                 getPrivilege($level);
                                                                                 header("Location: index.php");
                                                            }
                                                                                } else {
                                                                $_SESSION["danger"] = "Wrong ID or Password";
                                                                header("Location: login_page.php");
                }
                @ldap_close($ds);
        } else {
                $_SESSION["danger"] = "Wrong ID or Password 2";
                header("Location: login_page.php");
        }
    }
}
?>