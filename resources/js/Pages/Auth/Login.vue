<template>
  <div class="auth bg-cover h-screen flex justify-center items-center">
    <div class="fixed w-screen h-screen z-10 bluebg flex items-center justify-center" v-if="show">
      <div class="anima">
        <img class="w-32 block" src="/img/logo2.svg" />
      </div>
    </div>

    <div class="w-full grid grid-cols-1 gap-2 place-items-center sm:grid-cols-2" v-if="!show">
      <img class="px-50 w-48" src="/img/logo.svg" />
      <form class="mt-8 bg-white rounded-2xl overflow-hidden font-light shadow-sm" @submit.prevent="login">
        <div class="px-5 py-12 sm:px-10 py-12">
          <h3 class="text-center font-medium text-2xl">ВХОД В ЛИЧНЫЙ КАБИНЕТ</h3>

          <text-input v-model="form.email" :error="form.errors.email" class="mt-10" label="Логин" type="email" autofocus autocapitalize="off" />
          <br />
          <div class="passwordField" style="position: relative">
            <text-input id="show" name="show" v-model="form.password" type="password" class="mt-6" v-bind:value="value" v-on:input="$emit('input', $event.target.value)" label="Пароль" />
            <a id="eyeshow" @click="showPassword" class="absolute -top-1 right-0"><img class="h-5 opacity-30 mt-1" src="img/eyes/private.png" /></a>
            <a id="eyehide" @click="hidePassword" class="absolute -top-1 right-0" style="visibility: hidden"><img class="h-5 mt-1 opacity-30" src="img/eyes/view.png" /></a>
          </div>

          <label class="mt-6 select-none flex items-center" for="remember">
            <input id="remember" v-model="form.remember" class="mr-1" type="checkbox" />
            <span class="text-sm">Запомнить</span>
          </label>
          <div class="flex flex-col gap-4 mt-4 align-items-center">
            <button :loading="form.processing" class="login_button rounded-full text-white h-10 mb-2 hover:bg-indigo-600" type="submit">Войти</button>
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
import { messaging, getToken } from '../../firebase' // Ensure the correct path
import axios from "axios";

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
    setTimeout(() => {
      this.show = false
    }, 3000)
  },
  methods: {
    async getPushToken() {
      
      try {
        const token = await getToken(messaging, {
          vapidKey: 'BIjA-QF_FCv5mRPd74Ma-S6sQ7t0F8MN69gwDWld0d_sSa40vnoc975eaMnMKnNiFdiJCRydboDImXT-vz6_MBE',
        })
        if (token) {
          console.log('FCM Token:', token)

         
          this.sendTokenToServer(token)
        } else {
          
          console.log('No registration token available. Request permission to generate one.')
        }
      } catch (error) {
        console.error('Error getting FCM token:', error)
      }
    },

    async sendTokenToServer(token) {
      try {
        this.$page.props.auth.user.token = token
        await axios.post('/api/pushtoken', { token, user_id: this.$page.props.auth.user.id }).then((response) => {
         
        })
      } catch (e) {
        console.log('Error while sending token to server', e)
      }
    },

    post() {
      alert('технические работы')
    },
    forget() {
      alert('технические работы')
    },

    showPassword() {
      var pas = document.getElementById('show')
      var eye = document.getElementById('eyeshow')
      var eye1 = document.getElementById('eyehide')
      pas.type = 'text'
      eye.style.visibility = 'hidden'
      eye1.style.visibility = 'visible'
    },
    hidePassword() {
      var pas = document.getElementById('show')
      var eye = document.getElementById('eyeshow')
      var eye1 = document.getElementById('eyehide')
      pas.type = 'password'
      eye.style.visibility = 'visible'
      eye1.style.visibility = 'hidden'
    },
    login() {
      this.form
        .transform((data) => ({
          ...data,
          remember: data.remember ? 'on' : '',
        }))
        .post(this.route('login.attempt'), {
          onSuccess: (data) => {
            console.log('data', data)
            this.getPushToken()
          },
        })
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
    transform: scale(1.03);
  }
  to {
    opacity: 1;
  }
}

@keyframes tesxs {
  from {
    filter: contrast(1);
  }
  50% {
    filter: contrast(1.1);
    transform: scale(1.01);
  }
  to {
    filter: contrast(1);
  }
}
</style>
