<?php

class Components extends Plugin {

    
    public function adminView()
    {

        $folder        = PATH_CONTENT.'components';


           // Token for send forms in Bludit
           global $security;
           $tokenCSRF = $security->getTokenCSRF();

           echo '<script src="https://cdn.ckeditor.com/4.19.1/standard/ckeditor.js"></script>';
    

        $html = '
        
        <div class="bg-dark p-3 text-light mb-4">

        <h4>How use it?</h4>

        <ul style="margin:0;padding:0;margin-left:15px;list-style-type:square;">
<li>Create new component</li>
<li>Edit component</li>
<li>Save all</li>
<li> Copy and paste function on template where you want use it</li>
        </ul>

        </div>

        <form method="post" action="#">
        <div class="col-md-12 bg-light p-3 border">

        <h4><i>Create new component</i></h4>
<hr>
        <input type="hidden" id="jstokenCSRF" name="tokenCSRF" value="'.$tokenCSRF.'">

        <input type="text" name="componentsname" class="form-control mb-2" placeholder="Component Name">

        <textarea name="componentscontent" class="form-control mb-2" placeholder="Component Content" style="height:200px;"></textarea>

        <div class="my-1 mt-3">
        <input type="checkbox" name="wysywig"> Wysywig?

        </div>

<br>
        <input type="submit" value="create new component" class="btn btn-lg btn-dark rounded px-5" name="createcomponent">

        </div>
        </form>
   

        ';
        


        echo $html; 

        


        if(isset($_POST['createcomponent'])){


            if(isset($_POST['wysywig'])){
                $newNameComponents = strtolower(str_replace(' ', '-', $_POST['componentsname']).'_wysywig'); 
            }else{
                $newNameComponents = strtolower(str_replace(' ', '-', $_POST['componentsname'])); 
            };


            $filename      = $folder .DS.$newNameComponents.'.txt';
            $chmod_mode    = 0755;
            $folder_exists = file_exists($folder) || mkdir($folder, $chmod_mode);
             
            // Save the file (assuming that the folder indeed exists)
            if ($folder_exists) {
              file_put_contents($filename, $_POST['componentscontent']);
              echo("<meta http-equiv='refresh' content='0'>");
            
            }
            
}



//components edit




        $filenames = glob($folder.DS.'*.txt');




foreach ($filenames as $filename) {

    $base = basename($filename);

    $newname = substr($base, 0, -4);


    echo '<form method="post" class="bg-light p-3 mt-3 border">
    <input type="hidden" id="jstokenCSRF" name="tokenCSRF" value="'.$tokenCSRF.'">

    <input type="text"  style="display:none;" name="dir" value="'.$filename.'">

    <h5><i>'.$newname.'</i></h5>


    <code class="copycode" style="background:#fafafa;border:solid 1px #ddd;width:100%;display:block;margin-bottom:10px;box-sizing:border-box;padding:10px;">
    &#60;?php getComponents("'.$newname.'");?&#62;
     </code>';
    


   echo ' <textarea id="'.$newname.'" name="editcontent" class="form-control mb-3 " style="height:220px">'.file_get_contents($filename).'</textarea>


   <div class=" mt-3 d-block">

   <input type="submit" name="savechanges" value="save changes" class="btn btn-primary">
   <input type="submit" name="deletefile"   onclick="return confirm(`Are you sure you want to delete?`)"  class="btn btn-danger" value="Delete component">

   </div>

    </form>';



    if(strpos($newname, '_wysywig') !== false){
        echo "
 <script>
 CKEDITOR.replace( '".$newname."' );
 </script>
            ";
};


if(isset($_POST['savechanges'])){
    file_put_contents($_POST['dir'],$_POST['editcontent']);
    echo("<meta http-equiv='refresh' content='0'>");
};

 if(isset($_POST['deletefile'])){
unlink($_POST['dir']);
echo("<meta http-equiv='refresh' content='0'>");
    }



}







echo '<form action="https://www.paypal.com/cgi-bin/webscr" class="moneyshot" method="post" target="_top" style="display:flex; flex-direction:column; margin-top:10px;padding:20px;box-sizing:border-box;width:100%;align-items:center;justify-content:space-between;background:#D61C4E;color:#fff;">
    <p style="margin:0;padding:0;">If you want support my work  via paypal :) Thanks!</p>
    <input type="hidden" name="cmd" value="_s-xclick" />
    <input type="hidden" name="hosted_button_id" value="KFZ9MCBUKB7GL" />
    <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button" />
    <img alt="" border="0" src="https://www.paypal.com/en_PL/i/scr/pixel.gif" width="1" height="1" />
    
   
    </form>';

    }

    public function adminSidebar()
    {
        $pluginName = Text::lowercase(__CLASS__);
        $url = HTML_PATH_ADMIN_ROOT.'plugin/'.$pluginName;
        $html = '<a id="current-version" class="nav-link" href="'.$url.'">Edit Components</a>';
        return $html;
    }



}


function getComponents($name){
    $folder= PATH_CONTENT.'components';

    if(file_exists($folder.DS.$name.'.txt')){
        echo file_get_contents($folder.DS.$name.'.txt');
    }


}

;?>