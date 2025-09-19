<option value="">Select Sub Category</option>

@php

	if(isset($categories) && !empty($categories)){

    	foreach($categories as $key => $category){

@endphp

        	<option value="{{$key}}">{{$category}}</option>

@php    }

   	} die;

@endphp