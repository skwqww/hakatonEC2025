<script setup>
import Toast from 'primevue/toast';
import ConfirmDialog from 'primevue/confirmdialog';
import { onMounted } from 'vue'


import { useChatStore } from '@/stores/ChatStore.js'

const chatStore = useChatStore()

onMounted(() => {
  chatStore.pushMessages()
})
</script>

<template>
  <div class="bg-white min-h-screen h-full">
      <div class="m-auto max-w-[500px]">
        <ConfirmDialog/>
        <Toast style="width: 360px" position="top-right"/>
        <RouterView/>
      </div>
  </div>

</template>

<style scoped>

.main-enter-active {
  animation: main-enter 0.5s
}

@keyframes main-enter {
  0%{
    opacity: 0;
  }
  100%{
    opacity: 1;
  }
}

</style>
