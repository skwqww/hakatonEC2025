<script setup>

import ChatItem from '@/components/chatList/ChatItem.vue'

import { useChatStore } from '@/stores/ChatStore.js'

const chatStore = useChatStore()


</script>

<template>
  <div v-if="chatStore.allChatsData">
    <ChatItem v-for="chat in chatStore.allChatsData.chats"
              :key="chat.id"
              :id="chat.id"
              :name="chat.name"
              :time="chat.messages?.[0]?.created ?? ''"
              :message="chat.messages?.[0]?.message ?? ''"
              :chatId="chat.chat_id"
    />
  </div>

</template>

<style scoped>

</style>
