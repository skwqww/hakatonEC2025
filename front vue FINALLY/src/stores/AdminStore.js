import { defineStore } from 'pinia'
import { ref } from 'vue'
import axios from 'axios'

import { apiPath } from '@/helpers/values.js'

import { usePreloaderStore } from '@/stores/PreloaderStore.js'
import { useConfirm } from 'primevue/useconfirm'
import { useToast } from "primevue/usetoast";

export const useAdminStore = defineStore('AdminStore', () => {

  const preloaderStore = usePreloaderStore()


  const userIsAdmin = ref(false);

  const checkUserIsAdmin = async () => {

    try{
      const {data} = await axios.get(`${apiPath.path}/getUserRole`, {
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('userToken') ? localStorage.getItem('userToken') : sessionStorage.getItem('userToken')}`
        }
      })
      if(data.user === 'admin'){
        userIsAdmin.value = true
      }else{
        userIsAdmin.value = false
      }
    }catch(err){
      console.log(err)
    }
  }


  //   АДМИНКА ПОЛЬЗОВАТЕЛЯ

  const usersData = ref([])


  const getUsersData = async () => {
    preloaderStore.adminPreloaderStatus = true
    try{
      const {data} = await axios.get(`${apiPath.path}/admin/getUsers`, {
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('userToken') ? localStorage.getItem('userToken') : sessionStorage.getItem('userToken')}`
        }
      })
      console.log(data)
      usersData.value = data.users

    }catch(err){
      console.log(err)
    }finally {
      preloaderStore.adminPreloaderStatus = false
    }
  }



  // Редактирование в админке пользователя

  const refactorValue = ref()
  const refactorId = ref()
  const refactorData = ref()


  const editUserQuery = async () => {
    try{
      const {data} = await axios.post(`${apiPath.path}/admin/updateUser/${refactorId.value}`, refactorData.value,{
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('userToken') ? localStorage.getItem('userToken') : sessionStorage.getItem('userToken')}`
        }
      })
      console.log(data)
      await getUsersData()
      toast.add({
        severity: 'success',
        summary: 'Успешно',
        detail: 'Данные отредактированны',
        life: 3000
      });
    }catch(err){
      toast.add({
        severity: 'error',
        summary: 'Ошибка',
        detail: 'Ошибка редактирования',
        life: 3000
      });
      console.log(err)
    }
  }

  // Confirm

  const confirm = useConfirm()
  const toast = useToast()

  const confirmRefactorWindow = () => {
    confirm.require({
      message: 'Вы уверены что хотите отредактровать данные?',
      header: 'Редактирование',
      icon: 'pi pi-exclamation-triangle',
      rejectProps: {
        label: 'Отменить',
        severity: 'secondary',
        outlined: true
      },
      acceptProps: {
        label: 'Продолжить'
      },
      accept: async () => {
        await editUserQuery()
      }

    })
  }


  const onEditUser = async (event) => {
    let { data, newValue, field } = event;

    refactorValue.value = event.value
    refactorId.value = data.id
    refactorData.value = {[field]: newValue}


    if(refactorValue.value !== newValue){
      confirmRefactorWindow()
    }
  }

  // Удаление пользователя



  const confirmRemoveWindow = () => {
    confirm.require({
      message: 'Вы уверены что хотите удалить пользователя?',
      header: 'Удаление',
      icon: 'pi pi-exclamation-triangle',
      rejectProps: {
        label: 'Отменить',
        severity: 'secondary',
        outlined: true
      },
      acceptProps: {
        label: 'Продолжить',
        severity: 'danger'
      },
      accept: async () => {
        await removeUserQuery()
      }

    })
  }

  const removeUserId = ref()

  const onRemoveUser = (event) => {
    console.log(event.id)
    removeUserId.value = event.id
    confirmRemoveWindow()
  }


  const removeUserQuery = async () => {
    try{
      const {data} = await axios.post(`${apiPath.path}/admin/deleteUser/${removeUserId.value}`, '',{
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('userToken') ? localStorage.getItem('userToken') : sessionStorage.getItem('userToken')}`
        }
      })
      console.log(data)
      await getUsersData()
      toast.add({
        severity: 'success',
        summary: 'Успешно',
        detail: 'Пользователь удален',
        life: 3000
      });
    }catch(err){
      toast.add({
        severity: 'error',
        summary: 'Ошибка',
        detail: 'Ошибка удаления пользователя',
        life: 3000
      });
      console.log(err)
    }
  }

  const createUserData = ref({
    name: '',
    username: '',
    password: '',
    role: '',
    prefix: ''
  })

  const createUserQuery = async () => {
    try{
      console.log(createUserData.value)

      const {data} = await axios.post(`${apiPath.path}/admin/createUser`, createUserData.value,{
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('userToken') ? localStorage.getItem('userToken') : sessionStorage.getItem('userToken')}`
        }
      })
      console.log(data)
      await getUsersData()
      toast.add({
        severity: 'success',
        summary: 'Успешно',
        detail: 'Пользователь удален',
        life: 3000
      });
    }catch(err){
      toast.add({
        severity: 'error',
        summary: 'Ошибка',
        detail: 'Ошибка удаления пользователя',
        life: 3000
      });
      console.log(err)
    }
  }


  // Работа с чатами

  const allChatsData = ref()

  const getAllChats = async () => {
    preloaderStore.adminPreloaderStatus = true
    try{
      const {data} = await axios.get(`${apiPath.path}/admin/getChats`, {
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('userToken') ? localStorage.getItem('userToken') : sessionStorage.getItem('userToken')}`
        }
      })
      console.log(data)
      allChatsData.value = data

    }catch(err){
      console.log(err)
    }finally {
      preloaderStore.adminPreloaderStatus = false
    }
  }

  const createChatData = ref({
    name: ''
  })

  const createChatQuery = async () => {
    try{
      console.log(createChatData.value)

      const {data} = await axios.post(`${apiPath.path}/admin/storeChat`, createChatData.value,{
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('userToken') ? localStorage.getItem('userToken') : sessionStorage.getItem('userToken')}`
        }
      })
      console.log(data)
      await getAllChats()
      toast.add({
        severity: 'success',
        summary: 'Успешно',
        detail: 'Пользователь удален',
        life: 3000
      });
    }catch(err){
      toast.add({
        severity: 'error',
        summary: 'Ошибка',
        detail: 'Ошибка удаления пользователя',
        life: 3000
      });
      console.log(err)
    }
  }

  const addUserChatData = ref({
    chat_id: '',
    user_id: ''
  })

  const addUserChatQuery = async () => {
    try{
      console.log(addUserChatData.value)

      const {data} = await axios.post(`${apiPath.path}/admin/addUserChat`, addUserChatData.value,{
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('userToken') ? localStorage.getItem('userToken') : sessionStorage.getItem('userToken')}`
        }
      })
      console.log(data)
      await getAllChats()
      toast.add({
        severity: 'success',
        summary: 'Успешно',
        detail: 'Пользователь удален',
        life: 3000
      });
    }catch(err){
      toast.add({
        severity: 'error',
        summary: 'Ошибка',
        detail: 'Ошибка удаления пользователя',
        life: 3000
      });
      console.log(err)
    }
  }

  return {
    userIsAdmin,
    checkUserIsAdmin,
    usersData,
    getUsersData,
    onEditUser,
    removeUserId,
    onRemoveUser,
    createUserQuery,
    createUserData,
    allChatsData,
    getAllChats,
    createChatData,
    createChatQuery,
    addUserChatData,
    addUserChatQuery
  }
})
