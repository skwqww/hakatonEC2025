<script setup>

import ChatList from '@/components/chatList/ChatList.vue'

import { useRoute } from 'vue-router'

import { useChatStore } from '@/stores/ChatStore.js'
import { onMounted } from 'vue'

const chatStore = useChatStore()

const route = useRoute()

onMounted(() => {
  chatStore.getAllChats()
})

</script>

<template>
  <div>
    <transition name="main">
      <div v-if="!route.params.chatId">
        <div class="flex items-center gap-3">
          <h1 class="pt-4 ml-8 font-semibold text-2xl mb-4">Чаты</h1>
        </div>
        <ChatList/>
      </div>
    </transition>
    <transition name="main">
      <div v-if="route.params.chatId">
        <router-view></router-view>
      </div>
    </transition>
  </div>
</template>

<style scoped>

.main-enter-active{
  animation: main 0.5s
}

@keyframes main{
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
