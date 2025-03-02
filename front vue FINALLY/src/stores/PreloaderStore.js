import { defineStore } from 'pinia'
import { ref } from 'vue'

export const usePreloaderStore = defineStore('preloaderStore', () => {

  const preloaderStatus = ref(false)

  const adminPreloaderStatus = ref(false)

  return {
    preloaderStatus,
    adminPreloaderStatus,
  }

})
