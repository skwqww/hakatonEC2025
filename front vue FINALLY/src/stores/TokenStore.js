import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useTokenStore = defineStore('userToken', () => {
  const userToken = ref(sessionStorage.getItem('userToken'))

  const setUserToken = () => {
    userToken.value = sessionStorage.getItem('userToken')
  }


  return {
    userToken,
    setUserToken
  }
})
