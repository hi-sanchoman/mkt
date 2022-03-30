<template>
	<div>
		<div class="sm:p-4 bg-white rounded-lg">
			<center>
				<img class="rounded-full w-1/2 pt-5" :src="'/' + $page.props.auth.user.photo_path" >
				<h2 class="mt-5">{{$page.props.auth.user.first_name}} {{$page.props.auth.user.last_name}}</h2>
				<h3 class="mt-5 pb-5">{{position.name}}</h3>
			</center>

		</div>
	</div>
</template>

<script>
	
import Layout from '@/Shared/Layout'
import axios from 'axios'
import $ from 'jquery'
import Datepicker from 'vue2-datepicker'
import 'vue2-datepicker/index.css'

export default {
    metaInfo: {
        title: 'Dashboard'
    },
    
    layout: Layout,
    data() {

        return {
            
        }
    },
    props: {
    	position: Array
    },
    mounted(){

 
    },
    created() {


    },
    components: {
      Datepicker
    },
    watch: {

    },
    computed: {

    },
    methods: {
    }
}
</script>