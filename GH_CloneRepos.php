<?php

/* GH_CloneRepos.php - Clone users public GitHub repositories.

Copyright (c) 2015, Mikael Ikivesi
All rights reserved.

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are met:
    * Redistributions of source code must retain the above copyright
      notice, this list of conditions and the following disclaimer.
    * Redistributions in binary form must reproduce the above copyright
      notice, this list of conditions and the following disclaimer in the
      documentation and/or other materials provided with the distribution.
    * Neither the name of the <organization> nor the
      names of its contributors may be used to endorse or promote products
      derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL <COPYRIGHT HOLDER> BE LIABLE FOR ANY
DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

*/



function get_repos($u){
    $c=curl_init();
    curl_setopt($c,CURLOPT_URL,$u);
    curl_setopt($c,CURLOPT_USERAGENT, "GH_CloneRepos.php");
    curl_setopt($c,CURLOPT_HEADER, 0);
    curl_setopt($c,CURLOPT_RETURNTRANSFER, true);
    curl_setopt($c,CURLOPT_TIMEOUT, 10);
    $o=curl_exec($c);
    curl_close($c);
    return $o;
}

//Check requirements and cli argument
if(!extension_loaded('json') || !extension_loaded('curl')) die ("You PHP needs to have json and curl extensions installed.\n");
if(!`which git`) die ("You need to have git installed.\n");
if(!isset($argv[1])) die("Usage: ".$argv[0]." username\n");
$USER=$argv[1];
if(file_exists($USER)) die ("Error: Directory $USER exists.\n");

//START
if(!mkdir($USER)) die ("Error: Cannot create directory $USER.\n");

$i=1;
while(true)
    {$repos=json_decode(get_repos("https://api.github.com/orgs/$USER/repos?page=$i&per_page=100"));
     if($repos==NULL)
        {if ($i==1) print "Could not get public repositories for $USER!\n";
         else print "\n\nALL DONE!\n";
         exit(0);
        }
     $num=1;
     foreach($repos as $r)
      if($r->private==false)
        {$cu=$r->clone_url;
         $n=$r->name;
         print "\n\n---\nStarting cloning for: $n\n";
         exec("git clone $cu $USER/$n",$output,$retval);
         if($retval!=0) die ("Error: git cloning failed.\n");
         $num++;
        }
     if($num<100) {print "\n\nALL DONE!\n"; exit(0);}
     $i++;
    }

?>

