<template>
  <div class="overflow-y-auto">
    
    <div class="mb-8 flex justify-start w-full p-8 mr-2 items-center border-b">
      <img :src="'/storage/' + user.photo_path" class="block w-32 h-32 rounded mr-4 shadow">
      <h1 class="font-bold text-xl">
        <p class="text-black">{{ user.last_name + ' ' + user.first_name }}</p>
        <p class="text-sm">{{ user.position.name }}</p>
      </h1>
    </div>
    
    <div class="bg-white rounded-md  overflow-hidden w-full px-8">

        <div class="flex flex-wrap mb-3 justify-start mt-3">

					<div class="w-3/12 space-y-6 py-2">
						<p class="font-medium">Имя</p>
					</div>
					<div class="w-9/12 space-y-4 pl-5 py-2">
						<input type="text" class="border-b-2 w-full pb-1" v-model="user.first_name">		
					</div>
          
        </div>

        <div class="flex flex-wrap mb-3 justify-start mt-3">

					<div class="w-3/12 space-y-6 py-2">
						<p class="font-medium">Фамилия</p>
					</div>
					<div class="w-9/12 space-y-4 pl-5 py-2">
						<input type="text" class="border-b-2 w-full pb-1" v-model="user.last_name">		
					</div>
          
        </div>

        <div class="flex flex-wrap mb-3 justify-start mt-3">

					<div class="w-3/12 space-y-6 py-2">
						<p class="font-medium">Email</p>
					</div>
					<div class="w-9/12 space-y-4 pl-5 py-2">
						<input type="text" class="border-b-2 w-full pb-1" v-model="user.email">		
					</div>
          
        </div>



        <div class="p-8 -mr-6 -mb-8 flex flex-wrap" v-if="$page.props.auth.user.id == user_id">
          <text-input v-model="user.first_name" class="pr-6 pb-8 w-full lg:w-1/2" label="First name" />
          <text-input v-model="user.last_name"  class="pr-6 pb-8 w-full lg:w-1/2" label="Last name" />
          <text-input v-model="user.email" class="pr-6 pb-8 w-full lg:w-1/2" label="Email" />
          <text-input v-model="user.password" class="pr-6 pb-8 w-full lg:w-1/2" type="password" autocomplete="new-password" label="Password" />
          <file-input v-model="user.photo_path"  class="pr-6 pb-8 w-full lg:w-1/2" type="file" accept="image/*" label="Photo" />
        </div>

        <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex items-center" v-if="$page.props.auth.user.id == user_id">
          <loading-button class="btn-indigo ml-auto">Сохранить</loading-button>
        </div>
  
    </div>
  </div>
</template>

<script>
import Layout from '@/Shared/Layout'
import TextInput from '@/Shared/TextInput'
import FileInput from '@/Shared/FileInput'
import SelectInput from '@/Shared/SelectInput'
import LoadingButton from '@/Shared/LoadingButton'
import TrashedMessage from '@/Shared/TrashedMessage'
import axios from 'axios'

export default {
  metaInfo() {
    return {
      title: 'Профиль',
    }
  },
  components: {
    FileInput,
    LoadingButton,
    SelectInput,
    TextInput,
    TrashedMessage,
  },
  props: {
    user_id: Number,
  },
  created(){
    axios.get('/get-profile/' + this.$page.props.actions.selected_user)
    .then(response => {
      this.user = response.data
    })

  },
  data() {
    return {
      user: {
        first_name: 'Имя',
        last_name: 'Фамилия',
        email: 'test@gmail.com',
        password: '',
        photo_path: '',
        position: {
          name: 'Должность'
        },
        deleted_at: null,
        processing: null,
      },
      
    }
  },
  methods: {
    update() {
      this.user.post(this.route('users.update', this.user.id), {
        onSuccess: () => this.user.reset('password', 'photo'),
      })
    },
    destroy() {
      if (confirm('Are you sure you want to delete this user?')) {
        this.$inertia.delete(this.route('users.destroy', this.user.id))
      }
    },
  },
}
</script>
