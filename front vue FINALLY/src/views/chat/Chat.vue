<script setup>

import MessageList from '@/components/chat/MessageList.vue'
import MessageInput from '@/components/chat/MessageInput.vue'
import ChatHeader from '@/components/chat/ChatHeader.vue'

import { nextTick } from 'vue'

import { onMounted } from 'vue'
import { useRoute } from 'vue-router'

import { useChatStore } from '@/stores/ChatStore.js'
import { useProfileStore } from '@/stores/ProfileStore.js'

const chatStore = useChatStore()
const profileStore = useProfileStore()

const route = useRoute()

onMounted(async() => {
  await profileStore.getUserData()
  await chatStore.getAllMessages(route.params.chatId)
  chatStore.setChatName(route.params.chatId)

  nextTick(() => {
    window.scrollTo({
      top: document.documentElement.scrollHeight// Плавная прокрутка
    });
  })
})


</script>

<template>
    <div class="relative">
      <ChatHeader v-if="chatStore.messages" @click="console.log(chatStore.messages)" class="fixed top-0 max-w-[500px] w-full z-40"/>
      <MessageList class="pt-20"/>
      <MessageInput class="fixed bottom-[80px]"/>
    </div>
</template>

<style scoped>

</style>
