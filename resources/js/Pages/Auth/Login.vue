<template>
  <div class="auth bg-cover h-screen flex justify-center items-center ">
  

      <div class="fixed w-screen h-screen z-10 bluebg flex items-center justify-center" v-if="show">
        <div class="anima">
          <img class="w-32  block" src="/img/logo2.svg">
        </div>
         
      </div>




    <div class="w-full grid grid-cols-1 gap-2 place-items-center sm:grid-cols-2" v-if="!show">
      <img class="px-50 w-48" src="/img/logo.svg">
      <form class="mt-8 bg-white rounded-2xl overflow-hidden font-light shadow-sm" @submit.prevent="login">
        <div class="px-5 py-12 sm:px-16 py-12">
          <h3 class="text-center font-light text-2xl">ВХОД В ЛИЧНЫЙ КАБИНЕТ</h3>

          <text-input v-model="form.email" :error="form.errors.email" class="mt-10" label="Логин" type="email" autofocus autocapitalize="off" />
          <br>
          <div class="passwordField" style="position:relative;">
            <text-input id="show" name="show" v-model="form.password" type="password" class="mt-6"  v-bind:value="value"  v-on:input="$emit('input', $event.target.value)" label="Пароль" />
            <a id="eyeshow" @click="showPassword" class="absolute -top-1 right-0"><img class="h-5 opacity-30 mt-1" src="img/eyes/private.png"></a>
            <a id="eyehide" @click="hidePassword" class="absolute -top-1 right-0" style="visibility:hidden;"><img class="h-5 mt-1 opacity-30" src="img/eyes/view.png"></a>
          </div>
          

          
           
            
           
     
          

          <label class="mt-6 select-none flex items-center" for="remember">
            <input id="remember" v-model="form.remember" class="mr-1" type="checkbox" />
            <span class="text-sm">Запомнить</span>
           
          </label>
          <div class="flex flex-col gap-4 mt-4 align-items-center">
            <button :loading="form.processing" class="login_button rounded-full text-white h-12 mb-2 hover:bg-indigo-600" type="submit">Войти</button>

            <!-- <a class="hover:bg-gray-200 bg-gray-100 h-12 lh-12 rounded-full text-gray-500  flex justify-center inline-block items-center" tabindex="-1" @click="forget"><p>Забыли пароль?</p></a>

            <center class="text-gray-500">или</center>
            <a class="hover:bg-gray-600 rounded-full h-12 bg-gray-500 text-white flex justify-center items-center" tabindex="-1" @click="post"><p>Войти через почту</p></a> -->
          </div>  
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import Logo from '@/Shared/Logo'
import TextInput from '@/Shared/TextInput'
import LoadingButton from '@/Shared/LoadingButton'

export default {
  metaInfo: { title: 'Вход' },
  components: {
    LoadingButton,
    Logo,
    TextInput,
  },
  data() {
    return {
      value: '',
      show: true,
      form: this.$inertia.form({
        email: null,
        password: null,
        remember: false,
      }),
    }
  },
  created() {
    setTimeout(()=> {
      this.show = false;
    }, 3000)
  },
  methods: {
    post(){
      alert("технические работы");
    },
    forget(){
      alert("технические работы");
    },

    showPassword() {
      var pas = document.getElementById("show");
      var eye = document.getElementById("eyeshow");
      var eye1 = document.getElementById("eyehide");
            pas.type = 'text';
            eye.style.visibility = "hidden";
            eye1.style.visibility = "visible";
    },
    hidePassword() {
            var pas = document.getElementById("show");
            var eye = document.getElementById("eyeshow");
            var eye1 = document.getElementById("eyehide");
            pas.type = 'password';
            eye.style.visibility = "visible";
            eye1.style.visibility = "hidden";
    },
    login() {
      this.form
        .transform(data => ({
          ...data,
          remember: data.remember ? 'on' : '',
        }))
        .post(this.route('login.attempt'))
    },
  },
}
</script>

<style scoped>

.bluebg {
  background-image: url(/img/blue_bg.png);
  background-position: center;
  background-size: cover;
}
.anima {
  animation: 3s anima ease-in-out infinite;
}

.bluebg {
  animation: 2s tesxs ease infinite;
}

@keyframes anima {
  from {
    opacity: 1;
  } 
  40% {
    opacity: 0;
    transform: scale(1.03)
  }
  to {
    opacity: 1;
  }
}

@keyframes tesxs {
  from {
    filter: contrast(1)
  } 
  50% {
    filter: contrast(1.1);
    transform: scale(1.01)
  }
  to {
    filter: contrast(1)
  }
}
</style>
