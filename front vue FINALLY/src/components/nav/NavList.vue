<script setup>

import NavItem from '@/components/nav/NavItem.vue'

import { onMounted, ref} from 'vue'

import { useAdminStore } from '@/stores/AdminStore.js'

const adminStore = useAdminStore()


const navItems = ref([
  {
    icon: 'pi pi-comments',
    title: "Чаты",
    pathName: "Chats",
    show: true,
    active: false
  },
  {
    icon: 'pi pi-key',
    title: "Админ панель",
    pathName: "Admin",
    show: false,
    active: false
  },
  {
    icon: 'pi pi-user',
    title: "Профиль",
    pathName: "Profile",
    show: true,
    active: false
  }
])

const showPanel = ref(false)

const showNavItem = () => {
  if(adminStore.userIsAdmin){
    navItems.value[1].show = true
  }
}

onMounted(async () => {
  await adminStore.checkUserIsAdmin()
  showNavItem()
  showPanel.value = true
})




</script>

<template>
  <div v-if="showPanel" class="flex items-center w-full">
    <NavItem v-for="item in navItems"
             :key="item.title"
             :icon="item.icon"
             :title="item.title"
             :pathName="item.pathName"
             :show="item.show"
             :active="item.active"
    />
  </div>
</template>

<style scoped>


</style>
