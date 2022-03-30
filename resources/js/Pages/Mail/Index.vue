<template>
	<div class="relative">
    <div>
      <h1 class="mb-8 font-bold text-3xl">Почта</h1>
    </div>
    <div class="flex justify-between items-center">
      <select v-model="selectedFolder" class="form-select pr-6 mb-8 w-full lg:w-1/3" @change="selectFolder">
        <option value="all" v-if="selectedFolder == 'all'">Все письма</option>
        <template v-for="(folder, index) in folders">
          <option :value="folder.name" :key="index" :selected="selectedFolder == folder.name">{{ folder.name }}</option>
        </template>
      </select>
      <a class="btn-indigo" @click="newMailPage = true">Написать</a>

    </div>
    
    <div class="mails bg-white rounded-md shadow overflow-x-auto">
      
	    <table class="w-full whitespace-nowrap">
	        <tr class="text-left ">
	          <th class="px-6 pt-6 pb-4 font-bold">Id</th>
	          <th class="px-6 pt-6 pb-4 font-bold">От / Кому</th>
	          <th class="px-6 pt-6 pb-4 font-bold">Заголовок</th>
	          <th class="px-6 pt-6 pb-4 font-bold">Дата</th>
	        </tr>
          <tr class="text-left hover:bg-gray-100 focus-within:bg-gray-100" :class="{ 'bg-green-100': (mail.flags.seen === undefined)}" v-for="(mail, index) in mails" @click="showBody(mail)">
            <td class="px-6 pt-6 pb-4 border-t">{{ index + 1 }}</td>
            <td class="px-6 pt-6 pb-4 border-t">
              <div>{{ mail.from }}</div>
              <div>{{ mail.to }}</div>
            </td>
            <td class="px-6 pt-6 pb-4 border-t">{{ mail.title }} <span class="inline-block w-2 h-2 mr-2 bg-green-600 rounded-full" v-if="mail.flags.seen === undefined"></span></td>
            <td class="px-6 pt-6 pb-4 border-t">{{ mail.date }}</td>
          </tr>
    	</table>

      
      
    </div>

    <div v-if="pages > 1">
      <a :href="'/mail/' + selectedDriver + '/' + selectedFolder + '/' + parseFloat(currentPage - 1)" class="btn-indigo mt-8" v-if="currentPage != 1">Новее</a>
      <a :href="'/mail/' + selectedDriver + '/' + selectedFolder + '/' + parseFloat(currentPage + 1)" class="btn-indigo mt-8" v-if="pages != currentPage">Старее</a>
    </div>

    <div class="sidebar mailbody px-6 pt-6 pb-4" :class="{sidebaropen: !mainPage}">
      <a @click="backToMain" class="btn-indigo mb-8">К списку</a>
        <h2 class="mb-8 font-bold text-3xl">{{ selected.title }}</h2>
        <div class="bg-white rounded-md shadow overflow-x-auto  px-6 py-4 mb-5">
           
          <div>Флаги: {{ selected.flags }}</div>
          <div>От: {{ selected.from }}  Кому: {{ selected.to }}</div>
          <div>Дата: {{ selected.date }}</div> 
        </div>
        
        <div class="bg-white rounded-md shadow overflow-x-auto px-4 py-4">
          <div v-html="selected.body" class="m-auto max-content"></div>
        </div>
        
    </div>

    


    <div class="sidebar newmail px-6 pt-6 pb-4" :class="{newmailopen: newMailPage}">
      <button @click="newMailPage = false" class="btn-indigo mb-8">Скрыть</button>
        <h2 class="mb-8 font-bold text-3xl">Новое письмо</h2>
        <div class=" rounded-md shadow  mb-5">
           
          <text-input type="email" v-model="newmail.to" class="form-input" label="Кому" />
          <text-input type="text" v-model="newmail.subject" class="form-input" label="Тема" />
          <label class="form-label mt-3">Сообщение</label>
          <textarea class="form-textarea mb-3" v-model="newmail.text" style="height: 150px" />
        </div>
        
        
        <div>
          <button class="btn-indigo" @click="sendMail">Отправить</button>
        </div>
    </div>

  
      
    </div>
</template>

<script>
import Layout from '@/Shared/Layout'
import TextInput from '@/Shared/TextInput'
import SelectInput from '@/Shared/SelectInput'
import LoadingButton from '@/Shared/LoadingButton'
import TrashedMessage from '@/Shared/TrashedMessage'
import axios from 'axios'

export default {
  metaInfo() {
    return {
      title: `Задача`,
    }
  },
  components: {
    LoadingButton,
    SelectInput,
    TextInput,
    TrashedMessage,
  },
  layout: Layout,
  props: {
     mails: Array,
     folders: Array,
     currentFolder: String,
     pages: Number,
     currentPage: Number,
  },
  created() {
    this.selectedFolder = this.currentFolder
  },
  data() {
    return {
      mainPage: true,
      newmail: {},
      newMailPage: false,
      selectedFolder: 'all',
      selectedDriver: 'mail',
      selected: {}
    }
  },
  methods: {
    showBody(mail) {
      this.mainPage = false
      this.selected = mail
    },
    backToMain() {
      this.mainPage = true
      this.selected = {}
    },
    selectFolder() {
      axios.get('/axios/mail/' + this.selectedDriver + '/' + this.selectedFolder)
            .then(response => {
              this.mails = []
              if(response.data.mails.length > 0) this.mails = response.data.mails
              if(response.data.mails.folders > 0) this.folders = response.data.folders
              this.selectedFolder = response.data.currentFolder   
              this.selectedDriver = response.data.selectedDriver   
            })
      
    },
    sendMail() {
      axios.post('/axios/send-mail', {
        data: this.newmail
      })
        .then(response => {
          console.log('sent'); 
        })
    }
  },
}
</script>

<style scoped>
.btn-indigo,
table td {
  cursor: pointer;
} 
.max-content {
  width: max-content;
}
.btn-indigo {
  display: inline-block;
}
.relative {
  position: relative;
}
.sidebar {
  height: 100%;
  width:100%;
  width:calc(100% - 14rem);
  position: fixed;
  z-index: 2;
  top: 0;
  right: 0;
  transform: translateX(100%);
  background-color: #f1f5f9;
  overflow-x: hidden;
  padding-top: 60px;
  transition: 0.3s;
  box-shadow: 0 0 5px #d2d1ff;
}
.sidebaropen {
  transform: translateX(0);
}
.newmailopen {
  min-width: 50%;
  max-width: 400px;
  transform: translateX(0);
}
.scrollToTop {
  position: fixed;
  z-index: 3;
  right: 20px;
  width: 30px;
  height: 30px;
  background: #fff;
  color: #333;
  bottom: 20px;
  cursor: pointer;
  border-radius: 15px;
  display: flex;
  align-items:center;
  justify-content: center;
}
textarea.form-textarea {
  min-height: 500px;
}
</style>
