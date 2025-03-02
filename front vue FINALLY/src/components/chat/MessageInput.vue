<script setup>

import FileUpload from 'primevue/fileupload';

import { ref } from 'vue'
import { useRoute } from 'vue-router'

import { useChatStore } from '@/stores/ChatStore.js'

const chatStore = useChatStore()

const route = useRoute()

const file = ref()

</script>

<template>
  <div class="px-4 w-full flex items-center max-w-[500px] gap-3">
    <div class="w-full relative">
      <textarea v-model="chatStore.messageInput" rows="1" class="pt-2 pl-2 pr-10 text-white w-full bg-mainBlack border-slate-800 border-[1px] rounded-xl h-10 outline-none" type="text"></textarea>
      <button @click="chatStore.sendMessage(route.params.chatId), scrollToBottom"><i class="pi max-h-[100px] pi-send text-white absolute right-4 top-[9px] cursor-pointer" style="font-size: 20px"></i></button>
    </div>
    <FileUpload v-model="file" mode="basic" name="demo[]" url="/api/upload" accept="image/*" :maxFileSize="1000000" @upload="onUpload" :auto="true" chooseLabel="Файл" />
<!--    <div class="cursor-pointer transition hover:bg-mainElemsHover bg-mainElems w-12 h-10 rounded-4xl flex items-center justify-center pr-[2px]">-->
<!--      <i class="pi pi-send text-white"></i>-->
<!--    </div>-->
  </div>
</template>

<style scoped>
textarea::-webkit-scrollbar {
  display: none;
}
</style>
