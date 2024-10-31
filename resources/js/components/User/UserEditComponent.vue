<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import Cookies from "js-cookie";
import { thisUrl } from "../../api.js";

const router = useRouter();
let user = ref([]);
let avatar = ref(null);
let name = ref('');
let surname = ref('');
let password1 = ref('');
let password2 = ref('');

const nameError = ref('');
const surnameError = ref('');
const passwordError = ref('');
const formError = ref('');
const showSuccessModal = ref(false);

const validateForm = () => {
    nameError.value = '';
    surnameError.value = '';
    passwordError.value = '';
    formError.value = '';

    let isValid = true;

    if (!name.value) {
        nameError.value = 'Пожалуйста, введите имя.';
        isValid = false;
    }

    if (!surname.value) {
        surnameError.value = 'Пожалуйста, введите фамилию.';
        isValid = false;
    }

    if (password1.value) {
        if (password1.value.length < 8) {
            passwordError.value = 'Пароль должен содержать минимум 8 символов.';
            isValid = false;
        } else if (!/^[A-z0-9]+$/.test(password1.value)) {
            passwordError.value = 'Пароль должен содержать только латинские буквы и цифры.';
            isValid = false;
        } else if (/\s/.test(password1.value)) {
            passwordError.value = 'Пароль не должен содержать пробелы.';
            isValid = false;
        } else if (!/[A-Z]/.test(password1.value)) {
            passwordError.value = 'Пароль должен содержать хотя бы одну заглавную букву.';
            isValid = false;
        } else if (!/[a-z]/.test(password1.value)) {
            passwordError.value = 'Пароль должен содержать хотя бы одну строчную букву.';
            isValid = false;
        } else if (!/\d/.test(password1.value)) {
            passwordError.value = 'Пароль должен содержать хотя бы одну цифру.';
            isValid = false;
        } else if (password1.value !== password2.value) {
            passwordError.value = 'Пароли не совпадают.';
            isValid = false;
        }
    }

    return isValid;
};

const data = async () => {
    try {
        const token = Cookies.get('token');
        const response = await axios.post(`${thisUrl()}/user`, {
            headers: {
                Authorization: `Bearer ${token}`,
            }
        });

        const responseUser = await axios.post(`${thisUrl()}/user/edit`, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });
        user.value = responseUser.data.data;
        name.value = user.value.name;
        surname.value = user.value.surname;

    } catch (error) {
        console.error('Ошибка вывода', error);
        throw error;
    }
}

const onFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        avatar.value = file;
    }
};

const updateUser = async () => {
    formError.value = '';
    passwordError.value = '';
    if (!validateForm()) return;

    try {
        const token = Cookies.get('token');
        const formData = new FormData();
        formData.append('name', name.value);
        formData.append('surname', surname.value);
        formData.append('password1', password1.value);
        formData.append('password2', password2.value);
        if (avatar.value) {
            formData.append('avatar', avatar.value);
        }

        const response = await axios.post(`${thisUrl()}/user`, formData, {
            headers: {
                Authorization: `Bearer ${token}`,
            }
        });

        if (response.status === 200) {
            showSuccessModal.value = true;

            setTimeout(() => {
                showSuccessModal.value = false;
                router.push({ name: 'user' });
            }, 5000);
        }
    } catch (error) {
        console.error('Ошибка вывода', error);
        formError.value = 'Произошла ошибка при изменении данных.';
    }
}

onMounted(() => {
    data();
});
</script>

<template>
    <div class="container mt-4">
        <div class="w-50 mx-auto">
            <h3>Редактирование профиля</h3>
            <input type="text" v-model="name" class="form-control my-3" placeholder="Имя">
            <span v-if="nameError" class="text-danger">{{ nameError }}</span>

            <input type="text" v-model="surname" class="form-control my-3" placeholder="Фамилия" :key="user.surname">
            <span v-if="surnameError" class="text-danger">{{ surnameError }}</span>

            <input type="password" v-model="password1" class="form-control my-3" placeholder="Пароль">
            <span v-if="passwordError" class="text-danger">{{ passwordError }}</span>

            <input type="password" v-model="password2" class="form-control my-3" placeholder="Повторите пароль">
            <input type="file" @change="onFileChange" class="form-control my-3" placeholder="Выберите аватарку">
            <input type="submit" @click.prevent="updateUser" class="btn btn-success w-100" value="Изменить">
            <span v-if="formError" class="text-danger mt-3">{{ formError }}</span>
        </div>

        <div v-if="showSuccessModal" class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Изменение данных успешно</h5>
                    </div>
                    <div class="modal-body">
                        <p>Ваши данные были успешно изменены!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.modal.fade.show.d-block {
    opacity: 1;
    transition: opacity 1s ease-in-out;
}
</style>
