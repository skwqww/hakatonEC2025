import { defineStore } from 'pinia'

import { pusher } from '@/pusher/pusher.js'
import { nextTick, ref } from 'vue'



import { apiPath } from '@/helpers/values.js'

import { useConfirm } from 'primevue/useconfirm'
import { useToast } from "primevue/usetoast";

import { usePreloaderStore } from '@/stores/PreloaderStore.js'
import axios from 'axios'

export const useChatStore = defineStore('chatStore', () => {

  const confirm = useConfirm()
  const toast = useToast()

  const preloaderStore = usePreloaderStore()

  const messages = ref([]);

  const sockets = ref([])

  const pushMessages = () => {
    const channel = pusher.subscribe('my-channel');

    channel.bind('my-event', (message) => {
      console.log(message)
      sockets.value.push(message)
    })
  }


  const getAllMessages = async (id) => {
    preloaderStore.preloaderStatus = true
    try{
      const {data} = await axios.post(`${apiPath.path}/getMessages`,{chat_id: id}, {
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('userToken') ? localStorage.getItem('userToken') : sessionStorage.getItem('userToken')}`
        }
      })
      console.log(data)
      messages.value = data
    }catch(err){
      console.log(err)
    }finally{
      preloaderStore.preloaderStatus = false
    }
  }


  const allChatsData = ref()

  const getAllChats = async () => {
    preloaderStore.preloaderStatus = true
    try{
      const {data} = await axios.get(`${apiPath.path}/getChats`, {
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('userToken') ? localStorage.getItem('userToken') : sessionStorage.getItem('userToken')}`
        }
      })
      allChatsData.value = data
      console.log(allChatsData.value)
    }catch(err){
      console.log(err)
    }finally {
      preloaderStore.preloaderStatus = false
    }
  }

  const chatName = ref("Чат")

  const setChatName = (id) => {
      console.log(id)
  }

  // Отправка сообщения

  const messageInput = ref('')

  const sendMessage = async (chatId) => {
    try{
      const {data} = await axios.post(`${apiPath.path}/sendMessage`, {chat_id: chatId, message: messageInput.value}, {
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('userToken') ? localStorage.getItem('userToken') : sessionStorage.getItem('userToken')}`
        }
      })
      messageInput.value = ''

      nextTick(() => {
        window.scrollTo({
          top: document.documentElement.scrollHeight,
          behavior: "smooth"// Плавная прокрутка
        });
      })
      console.log(data)
    }catch(err){
      console.log(err)
    }
  }

  // Удаление сообщения

  const deleteMessageId = ref()
  const deleteMessageChatId = ref()

  const deleteMessage = async () => {
    try{
      const {data} = await axios.post(`${apiPath.path}/deleteMessage`, {message_id: deleteMessageId.value}, {
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('userToken') ? localStorage.getItem('userToken') : sessionStorage.getItem('userToken')}`
        }
      })
      messageInput.value = ''
      console.log(data)
      toast.add({ severity: 'success', summary: 'Успешно', detail: 'Сообщение успешно удалено', life: 3000 });
      await getAllMessages(deleteMessageChatId.value)
    }catch(err){
      console.log(err)
      toast.add({ severity: 'error', summary: 'Ошибка', detail: 'Ошибка удаления сообщения', life: 3000 });
    }
  }

  const deleteMessageConfirm = (id,chatId) => {
    deleteMessageId.value = id
    deleteMessageChatId.value = chatId
    console.log(deleteMessageId.value)
    confirm.require({
      message: 'Вы уверены что хотите удалить сообщение?',
      header: 'Удалить',
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
        await deleteMessage()
      }

    })
  }





  return {
    messages,
    sockets,
    pushMessages,
    getAllMessages,
    allChatsData,
    getAllChats,
    chatName,
    setChatName,
    messageInput,
    sendMessage,
    deleteMessageId,
    deleteMessageConfirm
  }

})
