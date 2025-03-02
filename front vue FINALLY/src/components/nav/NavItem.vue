<script setup>

defineProps({
  icon: String,
  title: String,
  pathName: String,
  show: Boolean,
  active: Boolean
})

</script>

<template>
  <div v-if="show" class="w-full">
    <router-link activeClass="border-b-mainElems bg-linear-to-t from-amber-200/20 to-blue-[#232323]"
                 exactActiveClass=""
                 :to="{name: pathName}" class="border-b-mainBlack border-b-3 flex items-center gap-1 flex-col bg-mainBlack py-3">
      <i class="text-white" style="font-size: 20px" :class="icon"></i>
      <h1 class="text-[13px] text-white text-center">{{title}}</h1>
    </router-link>
  </div>
</template>

<style scoped>

</style>
