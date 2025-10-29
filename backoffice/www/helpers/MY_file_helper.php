<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @author Indra
*/

/**
 * Fungsi untuk mendapatkan daftar directory di dalam suatu path
 */
if ( ! function_exists('get_dir_info'))
{
    function get_dir_info($path)
    {
        $allDir = array();
        $scanAllDir = scandir($path);
        foreach ($scanAllDir as $result) {
            if ($result === '.' or $result === '..') continue;

            if (is_dir($path . '/' . $result)) {
                //code to use if directory
                $allDir[] = $result;
            }
        }
        return $allDir;
    }
}


if ( ! function_exists('get_public_methods')) {
    /* Pass the name of the class, not a declared handler */
    function get_public_methods($pathToClass, $className)
    {
        $CI = &get_instance();
        $reflector = new ReflectionClass($className);
        $currentClassName = $CI->router->fetch_class();

        if($pathToClass == 'www/modules/pendaftaran/controllers/pendaftaran.php'){
//            echo "<pre>";print_r(get_declared_classes());
//            exit();
            return false;
        }
        //Jika Class pemanggil fungsi ini tidak sama dengan class yang ingin discanning, include Class yang akan discan
        if(strtolower($currentClassName) != strtolower($className)){
            if (class_exists($className)) {
//                $reflector = new ReflectionClass($className);
//                echo $reflector->getFileName() . ' ' . $reflector->getStartLine() . "\n";
//                echo $newClassFile;
//                exit();
            }

            include($pathToClass);//Coba include class
            unset($reflector);
        }


        /* Init the return array */
        $returnArray = array();

        $allMethods = null;
//        $allMethods = get_class_methods($className);

        if(!is_null($allMethods)){
            /* Iterate through each method in the class */
            foreach ($allMethods as $method) {

                /* Get a reflection object for the class method */
                $reflect = new ReflectionMethod($className, $method);

                /* For private, use isPrivate().  For protected, use isProtected() */
                /* See the Reflection API documentation for more definitions */
                if ($reflect->isPublic()) {
                    /* The method is one we're looking for, push it onto the return array */
                    array_push($returnArray, $method);
                }
            }
        }

        /* return the array to the caller */
        return $returnArray;
    }
}
/* End of file file_helper.php */
/* Location: ./system/helpers/file_helper.php */