<script setup>
import {ref, onMounted} from 'vue';
import axios from 'axios';
import {useRoute} from 'vue-router';
import {useRouter} from 'vue-router';

const router = useRouter();
const route = useRoute();
let user = ref([]);
let avatar = ref(null);
let name = ref('');
let surname = ref('');
let password1 = ref('');
let password2 = ref('');



const data = async () => {
    try {
        const token = document.cookie.split('; ').find(row => row.startsWith('token=')).split('=')[1];
        const response = await axios.post('http://localhost/kurs2.2/public/api/user',{
            headers: {
                Authorization: `Bearer ${token}`,
            }
        });
    } catch (error) {
        console.error('Ошибка вывода', error);
        throw error;
    }
}

const updateUser = async () => {
    try {
        const token = document.cookie.split('; ').find(row => row.startsWith('token=')).split('=')[1];
        const formData = new FormData();
        formData.append('name', name.value);
        formData.append('surname', surname.value);
        formData.append('password1', password1.value);
        formData.append('password2', password2.value);
        if (avatar.value) {
            formData.append('avatar', avatar.value);
        }

        const response = await axios.post('http://localhost/kurs2.2/public/api/user', formData, {
            headers: {
                Authorization: `Bearer ${token}`,
                }
        });
    } catch (error) {
        console.error('Ошибка вывода', error);
        throw error;
    }
}


onMounted(() => {
    data();
});

</script>

<template>
    <div class="w-25">
        <input type="text" v-model="name" class="form-control m-3" placeholder="Имя">
        <input type="text" v-model="surname" class="form-control m-3" placeholder="Фамилия">
        <input type="password" v-model="password1" class="form-control m-3" placeholder="Пароль">
        <input type="password" v-model="password2" class="form-control m-3" placeholder="Повторите пароль">
        <input type="file" @change="event => avatar = event.target.files[0]" class="form-control m-3" placeholder="Выберите аватарку">
        <input type="submit" @click.prevent="updateUser" class="btn btn-success m-3" value="Изменить">
    </div>
</template>

<style scoped>

</style>
