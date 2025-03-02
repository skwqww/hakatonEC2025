<script setup>

import { useProfileStore } from '@/stores/ProfileStore.js'
import { onMounted, ref } from 'vue'
import RefactorTime from '@/views/profile/RefactorTime.vue'

const profileStore = useProfileStore()

onMounted(() => profileStore.getUserData())

</script>

<template>
    <div v-if="profileStore.userData.user" class="pt-12 text-center relative mx-8">
      <div class="absolute right-0 text-mainText">
        <i @click="profileStore.confirmLogoutWindow()" class="pi pi-sign-out cursor-pointer text-rose-700"></i>
      </div>
      <img
        :src="profileStore.userData.user.avatar"
        class="w-24 h-24 mx-auto rounded-full border-4 border-gray-200"
        alt="User Avatar"
      />
      <h2 class="mt-4 text-xl font-semibold text-gray-800">{{profileStore.userData.user.name}}</h2>
      <p class="text-gray-500">{{profileStore.userData.user.prefix}}</p>
      <p v-if="profileStore.userData.user.role === 'employer'" class="mt-2 text-sm text-gray-400">Время работы: {{profileStore.userData.user.start_work}} - {{profileStore.userData.user.end_work}}
        <i @click="profileStore.showRefactorTime = !profileStore.showRefactorTime, profileStore.setDefaultInputTime(profileStore.userData.user.start_work, profileStore.userData.user.end_work)"
           class="text-gray-500 pi pi-pen-to-square cursor-pointer mr-3"></i>
      </p>
      <transition name="a">
        <RefactorTime v-if="profileStore.showRefactorTime"/>
      </transition>
    </div>
</template>

<style scoped>

.a-enter-active{
  animation: a 0.5s
}


@keyframes a{
  0%{
    opacity: 0;
    transform: scale(0.9)
  }
  100%{
    transform: scale(1);
    opacity: 1
  }
}

</style>
