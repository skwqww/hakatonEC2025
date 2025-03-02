<script setup>

import InputMask from 'primevue/inputmask';
import Button from 'primevue/button'

import { useProfileStore } from '@/stores/ProfileStore.js'

const profileStore = useProfileStore()


</script>

<template>
  <div class="mt-5">
    <form>
      <div class="flex justify-center gap-2">
        <div>
          <p>Начало</p>
          <InputMask style="width: 70px" id="basic" v-model="profileStore.inputTimeStart" mask="99:99" placeholder="00:00" />
        </div>
        <div>
          <p>Конец</p>
          <InputMask style="width: 70px" id="basic" v-model="profileStore.inputTimeEnd" mask="99:99" placeholder="00:00" />
        </div>
      </div>
      <Button class="mt-3" type="submit" label="Продолжить" @click="profileStore.changeTime()" :loading="profileStore.loadingChangeTime"/>
    </form>
  </div>
</template>

<style scoped>

</style>
