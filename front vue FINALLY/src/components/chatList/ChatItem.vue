<script setup>

defineProps({
  id: Number,
  name: String,
  time: String,
  message: Object
})

</script>

<template>
  <router-link :to="'/chats/' + id" class="border-b-[1px] w-full border-b-slate-200 flex items-start p-4 cursor-pointer hover:bg-mainBg1 transition">
    <div class="bg-mainElems rounded-2xl w-[70px] h-[60px]"></div>
    <div class="pl-3 w-full">
      <h2 class="font-semibold h-[30px] overflow-hidden text-black font-semibold">{{name}}</h2>
      <div class="flex justify-between items-center">
        <span class="h-[30px] overflow-hidden italic text-sm text-gray-500">{{message}}</span>
        <span class="text-xs text-gray-500">{{time}}</span>
      </div>
    </div>
  </router-link>
</template>

<style scoped>

</style>
