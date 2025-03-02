import { defineStore } from 'pinia'
import { ref } from 'vue'

import axios from 'axios'

import { apiPath } from '@/helpers/values.js'
import { useRouter } from 'vue-router'

import { useMiniApp } from 'vue-tg'

import { useToast } from 'primevue/usetoast';


export const useAuthStore = defineStore('authStore', () => {


  const router = useRouter()

  // Toast
  const toast = useToast();

  const toastSuccess = () => {
    toast.add({ severity: 'success', summary: 'Успешный вход', detail: 'Поздравляю вы успешно вошли в свой аккаунт', life: 3000 });
  };

  const toastError = () => {
    toast.add({ severity: 'error', summary: 'Ошибка входа', detail: 'Попробуйте ввести пароль еще раз', life: 3000 });
  };



  const {initDataUnsafe} = useMiniApp()

  const userName = ref('@' + initDataUnsafe.user.username)
  const name = ref(initDataUnsafe.user.first_name + initDataUnsafe.user.last_name)
  const avatar = ref(initDataUnsafe.user.photo_url)
  const hours = ref(new Date().getHours());


  const password = ref('')

  const rememberMeStatus = ref(false)

  const setUserToken = (token) => {
    if(rememberMeStatus.value) {
      localStorage.setItem('userToken', token)
    }else{
      sessionStorage.setItem('userToken', token)
    }
  }


  const buttonLoading = ref(false)

  const authUser = async () => {
    buttonLoading.value = true
    try{
      // const {data} = await axios.post(`${apiPath.path}/login`, {username: '@nikita45738', name: 'Никита', password: password.value, hours: hours.value})
      const {data} = await axios.post(`${apiPath.path}/login`, {username: userName.value, name: name.value, password: password.value, avatar: avatar.value, hours: hours.value})
      setUserToken(data.access_token)
      password.value = ''
      buttonLoading.value = false
      toastSuccess()
      router.push({name: 'Chats'})
    }catch(err){
      buttonLoading.value = false
      password.value = ''
      toastError()
      console.log(err)
    }
  }

  const userAuthStatus = () => {
    if(localStorage.getItem('userToken') || sessionStorage.getItem('userToken')){
      return true
    }
    return false
  }


  return {
    userName,
    password,
    authUser,
    rememberMeStatus,
    buttonLoading,
    userAuthStatus
  }
})
