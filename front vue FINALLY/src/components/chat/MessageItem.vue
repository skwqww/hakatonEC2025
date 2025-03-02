<script setup>

import { onMounted, ref } from 'vue'

import { useProfileStore } from '@/stores/ProfileStore.js'
import { useChatStore } from '@/stores/ChatStore.js'

import { useRoute } from 'vue-router'

const props = defineProps({
  message: Object
})

const route = useRoute()

const profileStore = useProfileStore()
const chatStore = useChatStore()


const userIsCreator = ref(false)

onMounted(() => {
  if(props.message.user_id === profileStore.userData.user.id){
    userIsCreator.value = true
    console.log('TRUUUE')
  }
})

</script>

<template>
  <transition name="main">
    <div v-if="message.chat_id == route.params.chatId && !message.isDeleted" class="flex items-end gap-2" :class="userIsCreator ? 'flex-row-reverse' : ''">
      <img :src="message.user.avatar" class="w-8 h-8 bg-mainElems rounded-3xl"/>
      <div class="relative max-w-[340px] pl-3 pb-3 pt-2 pr-15 rounded-xl border-[1px] border-gray-300 text-sm bg-gray-100 text-gray-900"
           :class="userIsCreator === true ? 'rounded-br-none' : 'rounded-bl-none'">
          <div class="flex items-end gap-2">
            <p class="text-mainElemsHover">{{message.user.name}}</p>
            <p class="text-xs text-gray-500">{{message.user.prefix}}</p>
          </div>

        <div>
          {{message.message}}
          <span :class="message.isUpdated ? 'italic' : ''" class="absolute bottom-1 right-2 text-xs text-gray-500">

            <span v-if="message.isUpdated" class="italic">ред.</span>{{message.created}}
          </span>
          <div v-if="userIsCreator" class="absolute top-2 right-3 flex items-end gap-2">
            <i  class="pi pi-pencil text-slate-600 cursor-pointer"></i>
            <i @click="chatStore.deleteMessageConfirm(message.id, route.params.chatId)" class="pi pi-trash text-rose-600 cursor-pointer"></i>
          </div>
        </div>
      </div>
    </div>
  </transition>
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
