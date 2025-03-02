import { defineStore } from 'pinia'

import { useConfirm } from "primevue/useconfirm";
import { ref } from 'vue'

export const useMessageStore = defineStore('messageStore', () => {

  const messageInput = ref('');

  const editMessage = async () => {

  }



  const removeMessage = async (messageId) => {
    console.log(message)
  }

  // Confirm window
  const confirm = useConfirm();

  const confirmRemoweMessageWindow = (messageId) => {
    confirm.require({
      message: 'Вы действительно хотите удалить сообщение?',
      header: 'Удалить',
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
        removeMessage(messageId)
      }
    })
  }

  return {

    messageInput,
    confirmRemoweMessageWindow
  }


})
