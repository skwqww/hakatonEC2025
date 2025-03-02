import { defineStore } from 'pinia'

import axios from 'axios'
import { useRouter } from 'vue-router'

import { useConfirm } from "primevue/useconfirm";

import { apiPath } from '@/helpers/values.js'

import { usePreloaderStore } from '@/stores/PreloaderStore.js'
import {useTokenStore} from '@/stores/TokenStore.js'

import { useToast } from 'primevue/usetoast';

import { ref } from 'vue'

export const useProfileStore = defineStore('profileStore', () => {


  const router = useRouter()


  const tokenStore = useTokenStore()
  const preloaderStore = usePreloaderStore()



  // Загрузка данных

  const userData = ref({})

  const getUserData = async () => {

    preloaderStore.preloaderStatus = true;
    try{
      console.log(localStorage.getItem('userToken') ? localStorage.getItem('userToken') : sessionStorage.getItem('userToken'))
      const {data} = await axios.get(`${apiPath.path}/getUser`, {
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('userToken') ? localStorage.getItem('userToken') : sessionStorage.getItem('userToken')}`
        }
      })
      userData.value = data
      console.log(userData.value)
    }catch(err){
      console.log(err)
    }finally {
      preloaderStore.preloaderStatus = false;
    }
  }

  //  Выход из профиля
  const clearStorage = () => {
    localStorage.removeItem('userToken')
    sessionStorage.removeItem('userToken')
    tokenStore.userToken = ''
    router.push('/auth')
  }

  const logoutProfile = async () => {
    tokenStore.userToken = localStorage.getItem('userToken') ? localStorage.getItem('userToken') : sessionStorage.getItem('userToken')
    preloaderStore.preloaderStatus = true
    try{
      const {data} = await axios.post(`${apiPath.path}/logout`,'', {
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('userToken') ? localStorage.getItem('userToken') : sessionStorage.getItem('userToken')}`
        }
      });
      clearStorage()
      preloaderStore.preloaderStatus = false
      console.log(data)
    }catch(err){
      preloaderStore.preloaderStatus = false
      console.log(err)
    }
  }



  // Confirm window
  const confirm = useConfirm();

  const confirmLogoutWindow = () => {
    confirm.require({
      message: 'Вы действительно хотите выйти с аккаунта?',
      header: 'Выход',
      icon: 'pi pi-info-circle',
      rejectLabel: 'Отменить',
      rejectProps: {
        label: 'Отменить',
        severity: 'secondary',
        outlined: true
      },
      acceptProps: {
        label: 'Продолжить',
        severity: 'danger'
      },
      accept: () => {
        logoutProfile()
      },
      reject: () => {
        console.log(34234)
      }
    })
  }


  // Изменение времени

  const showRefactorTime = ref(false)

  const inputTimeStart = ref()
  const inputTimeEnd = ref()

  const setDefaultInputTime = (start, end) => {
    if(start !== null && end !== null){}
    inputTimeStart.value = start
    inputTimeEnd.value = end
  }

  const loadingChangeTime = ref(false)

  const toast = useToast();

  const toastSuccess = () => {
    toast.add({ severity: 'success', summary: 'Время измененно', detail: 'Поздравляю вы успешно изменили свое рабочее время', life: 3000 });
  };

  const toastError = () => {
    toast.add({ severity: 'error', summary: 'Ошибка редактирования', detail: 'Проверьте данные на корректность', life: 3000 });
  };

  const changeTime = async () => {
    loadingChangeTime.value = true
    try{
      console.log(inputTimeStart.value)
      const {data} = await axios.post(`${apiPath.path}/setWorkTime `, {start_work: inputTimeStart.value, end_work: inputTimeEnd.value},{
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('userToken') ? localStorage.getItem('userToken') : sessionStorage.getItem('userToken')}`,
        }
      })
      console.log(data)
      toastSuccess()
      showRefactorTime.value = false
      await getUserData()
    }catch(err){
      toastError()
      inputTimeStart.value = ''
      inputTimeEnd.value = ''
      console.log(err)
    }finally{
      loadingChangeTime.value = false
    }
  }

  return {
    showRefactorTime,
    confirmLogoutWindow,
    userData,
    getUserData,
    inputTimeStart,
    inputTimeEnd,
    setDefaultInputTime,
    loadingChangeTime,
    changeTime

  }

})
