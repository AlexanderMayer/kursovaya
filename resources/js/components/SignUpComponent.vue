<script setup>
import {ref} from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router';

const router = useRouter();
const name = ref('');
const login = ref('');
const email = ref('');
const surname = ref('');

const signUp = async () => {
    try {
        const response = await axios.post(
            'api/user/store', {
                name : name.value,
                login : login.value,
                email : email.value,
                surname : surname.value,
            }
        ).then( res => {
            router.push({name: 'start'});
        })
    } catch (error) {
        console.error('Ошибка при регистрации',error);
        throw error;
    }
}

</script>

<template>
    <div class="w-25">
        <input type="text" v-model="name" class="form-control m-3" placeholder="Имя">
        <input type="text" v-model="surname" class="form-control m-3" placeholder="Фамилия">
        <input type="text" v-model="login" class="form-control m-3" placeholder="Логин">
        <input type="email" v-model="email" class="form-control m-3" placeholder="Почта">
        <input type="submit" @click.prevent="signUp" class="btn btn-success m-3" value="Зарегестрироваться">
    </div>
</template>

<style scoped>

</style>
