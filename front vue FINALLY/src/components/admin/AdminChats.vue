<script setup>

import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import { onMounted, ref } from 'vue'

import { useAdminStore } from '@/stores/AdminStore.js'
import Button from 'primevue/button'

import InputText from 'primevue/inputtext'
import Dialog from 'primevue/dialog'

import { usePreloaderStore } from '@/stores/PreloaderStore.js'

const adminStore = useAdminStore()
const preloaderStore = usePreloaderStore()

const modalStatus = ref(false)

const modalStatus2 = ref(false)

onMounted(() => {
  adminStore.getAllChats()
})

</script>

<template>
  <div v-if="!preloaderStore.adminPreloaderStatus">
    <div class="flex items-center gap-5">
      <Button label="Создать" @click="modalStatus = true"/>
      <Button label="Добавить" @click="modalStatus2 = true"/>
    </div>

    <Dialog v-model:visible="modalStatus" modal header="Создание чата" :style="{ width: '25rem' }">
      <div class="flex items-center gap-4 mb-4">
        <label for="name" class="font-semibold w-24">Название</label>
        <InputText v-model="adminStore.createChatData.name" id="name" class="flex-auto w-full" autocomplete="off" />
      </div>
      <div class="flex justify-end gap-2">
        <Button type="button" label="Отмена" severity="secondary" @click="modalStatus = false"></Button>
        <Button type="button" label="Продолжить" @click="adminStore.createChatQuery(), modalStatus = false"></Button>
      </div>
    </Dialog>


    <Dialog v-model:visible="modalStatus2" modal header="Создание чата" :style="{ width: '25rem' }">
      <div class="flex items-center gap-4 mb-4">
        <label for="name" class="font-semibold w-24">Id Чата</label>
        <InputText v-model="adminStore.addUserChatData.chat_id" id="name" class="flex-auto w-full" autocomplete="off" />
      </div>
      <div class="flex items-center gap-4 mb-4">
        <label for="name" class="font-semibold w-24">Id Пользователя</label>
        <InputText v-model="adminStore.addUserChatData.user_id" id="name" class="flex-auto w-full" autocomplete="off" />
      </div>
      <div class="flex justify-end gap-2">
        <Button type="button" label="Отмена" severity="secondary" @click="modalStatus2 = false"></Button>
        <Button type="button" label="Продолжить" @click="adminStore.addUserChatQuery(), modalStatus2 = false"></Button>
      </div>
    </Dialog>


    <DataTable :value="adminStore.allChatsData" tableStyle="min-width: 50rem">
      <Column removableSort sortable field="chat_id" header="Id"></Column>
      <Column removableSort sortable field="name" header="Название"></Column>
      <Column removableSort sortable field="users" header="Id Пользователей"></Column>
    </DataTable>
  </div>
</template>

<style scoped>

</style>
