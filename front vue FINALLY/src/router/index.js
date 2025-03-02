import { createRouter, createWebHistory } from 'vue-router'

import { routes } from '@/router/routes.js'

import { useAuthStore } from '@/stores/AuthStore.js'
import { useAdminStore } from '@/stores/AdminStore.js'


const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: routes,
  inkActiveClass: "active",
  linkExactActiveClass: "active"
})

router.beforeEach((to) => {

  const authStore = useAuthStore()
  const adminStore = useAdminStore()

  if(to.meta.needAuth && !authStore.userAuthStatus()){
    return {name: 'Auth'}
  }

  if(!to.meta.needAuth && authStore.userAuthStatus()){
    return {name: 'Chats'}
  }

  if(to.meta.needIsAdmin && !adminStore.userIsAdmin && authStore.userAuthStatus()){
    return {name: 'Chats'}
  }


})

export default router
