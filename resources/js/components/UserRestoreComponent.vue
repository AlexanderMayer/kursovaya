<script setup>
import {ref} from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router';

const router = useRouter();
const email = ref('');

const data = async () => {
    try {
        const response = await axios.post('http://localhost/kurs2.2/public/api/user/restore')
            .then( res => {
            router.push({name: 'login'});
        })
    } catch (error) {
        console.error('Ошибка при вводе данных',error);
        throw error;
    }
}

</script>

<template>
    <div class="w-25">
        <input type="email" v-model="email" class="form-control m-3" placeholder="Почта">
        <input type="submit" @click.prevent="data" class="btn btn-success m-3" value="Восстановить пароль">
    </div>
</template>

<style scoped>

</style>
