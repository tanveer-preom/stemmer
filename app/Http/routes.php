<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
	$contents = "";
	$text = "";
	$filepath =" ";
    return view('welcome',compact('filepath'))->with('contents',$contents)->with('text',$text);
});
Route::get('/dl', function () {
	$filepath = public_path().'/dl.zip';
    return Response::download($filepath);
});
Route::get('/download/{path}', function ($path) {
	$filepath = '/'.$path;
    return Response::download($filepath);
})->where('path', '(.*)');



Route::get('/rootld5', function () {
	$filepath = public_path().'/RootsLD5.txt';
    return Response::download($filepath);
});

Route::get('/index', function () {
	
		return view('index')->with('directory','home/tanveer') ;

});
Route::get('/index/{dir}', function ($dir) {
	
		return view('index')->with('directory',$dir) ;

})->where('dir', '(.*)');


Route::get('/generate', function () {
	$contents = "";
	$text = '';
	$filepath = " ";
    return view('welcome',compact('filepath'))->with('contents',$contents)->with('text',$text);
});

Route::post('/generate', function () {
	$stammerScriptDirectory = public_path().'/BanglaStemmer/Stemmer/Stemming.py';
	if(Input::hasFile('file'))
	{
		$file = Input::file('file');
		$text = file_get_contents($file->getRealPath());
		$rootOutputDirectory = public_path() . '/Data/Output';
		$hashKey = substr($text, -6).rand().''.microtime(true);
		$fileName = md5($hashKey);
		//$inputFileDirectory = $rootInputDirectory.'/'.$fileName.'.txt';
		$outputFileDirectory = $rootOutputDirectory.'/'.$fileName.'.txt';
		$text2 = str_replace("।"," ",$text);
		$text2 = str_replace(","," ",$text2);
		$text2 = str_replace("‘"," ",$text2);
		$words = preg_split("/[\s]+/", $text2);
		$stemmableWords = [];
		$count = 0;
		$opText = "";
		$blox = true;
		$query = "select prefix_words.word as pref_word, base_words.word as base_word  from prefix_words,base_words where base_word = id and (";
		for($i=0;$i<count($words);$i++)
		{

			$words[$i] = trim($words[$i]);
			if($words[$i] == "")
				continue;
			else
				$stemmableWords[$count++] = $words[$i];
			/*if($words[$i] == "" && $i == count($words)-1)
			{
				$query = $query.")";
				continue;
			}
			else
			{
				continue;
			}


			if(count($words)-1<=0)
			{
				$query = $query." pref_word = '".$words[$i]."')";
				break;	
			}
			else if($blox && $i != count($words)-1 )
			{
				$query = $query." pref_word = '".$words[$i]."' ";
				$blox = false;
			}
			else if($i != count($words)-1)
			{
				$query = $query." or pref_word = '".$words[$i]."' ";
			}

			*/
			
		}
		for ($i=0; $i < count($stemmableWords); $i++) { 
			# code...
			if($i == 0)
			{
				$query = $query." prefix_words.word = '".$words[$i]."' ";			
			}
			else if($i>0 && $i<=count($stemmableWords)-1)
			{
				$query = $query." or prefix_words.word = '".$words[$i]."' ";	
			}

		}
		$query = $query.")";
		$availableWords = DB::select($query);
		$wordHashes = [];
		foreach ($availableWords as $wds) {
    		$wordHashes[$wds->pref_word] = $wds->base_word;
		}
		$resultToShow = "";
		
		for($i=0;$i<count($stemmableWords);$i++)
		{	
			$baseWord = null;
			try
			{
				$baseWord = $wordHashes[$stemmableWords[$i]];
			}
			catch(Exception $e)
			{

			}
			if($baseWord == null || $baseWord == "")
				$baseWord = $stemmableWords[$i];
			$resultToShow = $resultToShow.$stemmableWords[$i]." : ".$baseWord."\n";

		}


		//$rootLD5FileDirectory = public_path().'/RootsLD5.txt';
		if(File::put($outputFileDirectory,$resultToShow))
		{
			$contents = File::get($outputFileDirectory);
			$filepath = '/Data/Output/'.$fileName.'.txt'; 
			return view('welcome',compact('filepath'))->with('contents',$resultToShow)->with('text',$text);

		}

	}
	else if(Input::has('text'))
	{
		$text = Input::get('text');
		$rootOutputDirectory = public_path() . '/Data/Output';
		$hashKey = substr($text, -6).rand().''.microtime(true);
		$fileName = md5($hashKey);
		//$inputFileDirectory = $rootInputDirectory.'/'.$fileName.'.txt';
		$outputFileDirectory = $rootOutputDirectory.'/'.$fileName.'.txt';
		$text2 = str_replace("।"," ",$text);
		$text2 = str_replace(","," ",$text2);
		$text2 = str_replace("‘"," ",$text2);
		$words = preg_split("/[\s]+/", $text2);
		$stemmableWords = [];
		$count = 0;
		$opText = "";
		$blox = true;
		$query = "select prefix_words.word as pref_word, base_words.word as base_word  from prefix_words,base_words where base_word = id and (";
		for($i=0;$i<count($words);$i++)
		{

			$words[$i] = trim($words[$i]);
			if($words[$i] == "")
				continue;
			else
				$stemmableWords[$count++] = $words[$i];
			/*if($words[$i] == "" && $i == count($words)-1)
			{
				$query = $query.")";
				continue;
			}
			else
			{
				continue;
			}


			if(count($words)-1<=0)
			{
				$query = $query." pref_word = '".$words[$i]."')";
				break;	
			}
			else if($blox && $i != count($words)-1 )
			{
				$query = $query." pref_word = '".$words[$i]."' ";
				$blox = false;
			}
			else if($i != count($words)-1)
			{
				$query = $query." or pref_word = '".$words[$i]."' ";
			}

			*/
			
		}
		for ($i=0; $i < count($stemmableWords); $i++) { 
			# code...
			if($i == 0)
			{
				$query = $query." prefix_words.word = '".$words[$i]."' ";			
			}
			else if($i>0 && $i<=count($stemmableWords)-1)
			{
				$query = $query." or prefix_words.word = '".$words[$i]."' ";	
			}

		}
		$query = $query.")";
		$availableWords = DB::select($query);
		$wordHashes = [];
		foreach ($availableWords as $wds) {
    		$wordHashes[$wds->pref_word] = $wds->base_word;
		}
		$resultToShow = "";
		
		for($i=0;$i<count($stemmableWords);$i++)
		{	
			$baseWord = null;
			try
			{
				$baseWord = $wordHashes[$stemmableWords[$i]];
			}
			catch(Exception $e)
			{

			}
			if($baseWord == null || $baseWord == "")
				$baseWord = $stemmableWords[$i];
			$resultToShow = $resultToShow.$stemmableWords[$i]." : ".$baseWord."\n";

		}


		//$rootLD5FileDirectory = public_path().'/RootsLD5.txt';
		if(File::put($outputFileDirectory,$resultToShow))
		{
			$contents = File::get($outputFileDirectory);
			$filepath = '/Data/Output/'.$fileName.'.txt'; 
			return view('welcome',compact('filepath'))->with('contents',$resultToShow)->with('text',$text);

		}


	}

    //return view('welcome');
});