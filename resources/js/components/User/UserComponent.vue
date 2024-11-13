<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRoute, useRouter } from 'vue-router';
import Cookies from 'js-cookie';
import { thisUrl } from "../../api.js";

const router = useRouter();
const route = useRoute();
let user = ref({});
let showDeleteConfirm = ref(false);
let showDeleteSuccess = ref(false);
let complaints = ref(null);

const data = async () => {
    try {
        const token = Cookies.get('token');
        const response = await axios.post(`${thisUrl()}/user/edit`, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });
        user.value = response.data.data;

        const avatarDefault = 'uploads/avatar.png';
        if (!user.value.avatar) {
            user.value.avatar = avatarDefault;
        }

        complaints = Math.abs((user.value.rate_decency / 5) - 20);

    } catch (error) {
        console.error('Ошибка вывода', error);
        throw error;
    }
};

function createLot() {
    const token = Cookies.get('token');
    if (!token) {
        router.push({ name: 'login' });
    } else {
        router.push({ name: 'create' });
    }
}

function userLots() {
    const token = Cookies.get('token');
    if (!token) {
        router.push({ name: 'login' });
    } else {
        router.push({ name: 'user.lots' });
    }
}

function userEdit() {
    const token = Cookies.get('token');
    if (!token) {
        router.push({ name: 'login' });
    } else {
        router.push({ name: 'user.edit' });
    }
}

const confirmDelete = () => {
    showDeleteConfirm.value = true;
};

const userDelete = async () => {
    try {
        const token = Cookies.get('token');
        await axios.delete(`${thisUrl()}/user/delete`, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });
        showDeleteConfirm.value = false;
        showDeleteSuccess.value = true;
        setTimeout(() => {
            showDeleteSuccess.value = false;
            router.push({ name: 'start' });
        }, 3000);
    } catch (error) {
        console.error('Ошибка при удалении пользователя', error);
    }
};

onMounted(() => {
    data();
    const token = Cookies.get('token');
    if (!token) {
        router.push({ name: 'login' });
    }
});
</script>

<template>
    <div class="container my-4" v-show="user">
        <div class="row">

            <div class="col-md-3 d-flex align-items-center">
                <div class="w-100">
                    <div v-if="user.avatar">
                        <img class="rounded user-avatar" :src="`./storage/${user.avatar}`" alt="Фото пользователя">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <h3>{{ user.name }} {{ user.surname }}</h3>
                <p><strong>Логин:</strong> {{ user.login }}</p>
                <p><strong>Жалоб:</strong> {{ complaints }}</p>
                <div class="my-3">
                    <h5>Ваш рейтинг:</h5>
                    <div class="progress">
                        <div
                            class="progress-bar"
                            role="progressbar"
                            :class="{
                                'bg-danger': user.rate_decency <= 25,
                                'bg-warning': user.rate_decency > 25 && user.rate_decency <= 50,
                                'bg-info': user.rate_decency > 50 && user.rate_decency <= 75,
                                'bg-success': user.rate_decency > 75
                            }"
                            :style="{ width: user.rate_decency + '%' }"
                            :aria-valuenow="user.rate_decency"
                            aria-valuemin="0"
                            aria-valuemax="100">
                            {{ user.rate_decency }}%
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 d-flex flex-column justify-content-evenly">
                <button @click.prevent="createLot" class="btn btn-success mb-2">Добавить лот</button>
                <button @click.prevent="userEdit" class="btn btn-success mb-2">Редактировать профиль</button>
                <button @click.prevent="userLots" class="btn btn-success mb-2">Посмотреть мои лоты</button>
                <button @click.prevent="confirmDelete" class="btn btn-danger">Удалить профиль</button>
            </div>
        </div>
    </div>

    <div v-if="showDeleteConfirm" class="modal-overlay">
        <div class="modal-content">
            <p>Вы уверены, что хотите удалить профиль?</p>
            <button @click="userDelete" class="btn btn-danger mb-2">Удалить</button>
            <button @click="showDeleteConfirm = false" class="btn btn-secondary">Отмена</button>
        </div>
    </div>

    <div v-if="showDeleteSuccess" class="modal-overlay">
        <div class="modal-content">
            <p>Ваш профиль успешно удалён</p>
        </div>
    </div>
</template>

<style scoped>
.container {
    max-width: 1820px;
}
.progress {
    height: 25px;
    max-width: 300px;
    border-radius: 0.5rem;
}
.progress-bar {
    line-height: 25px;
    font-size: 1rem;
}
h3 {
    font-size: 1.5rem;
    margin-bottom: 1rem;
}
.user-avatar {
    width: 100%;
    height: auto;
}
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}
.modal-content {
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    text-align: center;
    max-width: 400px;
}
button.btn {
    margin: 0 10px;
}
</style>
