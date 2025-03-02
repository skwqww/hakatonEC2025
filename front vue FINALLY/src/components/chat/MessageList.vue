<script setup>

import MessageItem from '@/components/chat/MessageItem.vue'

import { useChatStore } from '@/stores/ChatStore.js'
const chatStore = useChatStore()


</script>

<template>
  <div class="flex flex-col gap-5 px-4 py-10 overflow-auto">

      <MessageItem v-for="message in chatStore.messages.messages"
                   :key="message.id" :message="message"
      />
    <MessageItem v-for="message in chatStore.sockets"
                 :key="message.id" :message="message"
    />


  </div>

</template>

<style scoped>



</style>
