<template>
  <div class="container">
    <el-form
      ref="dataForm"
      :model="place"
      label-position="left"
      style="width: 500px; margin-left: 250px"
    >
      <el-form-item label="Tên địa điểm" prop="name">
        <el-input v-model="place.name" type="textarea" autosize />
      </el-form-item>
      <el-form-item label="Chọn Tỉnh/Thành phố" prop="province">
        <el-select
          v-model="place.province"
          class="m-2"
          placeholder="Tỉnh/Thành phố"
          size="large"
          @change="onProvinceChange"
        >
          <el-option
            v-for="item in addressData"
            :key="item.Name"
            :label="item.Name"
            :value="item.Name"
          />
        </el-select>
      </el-form-item>
      <el-form-item label="Chọn Quận/Huyện" prop="district">
        <el-select
          v-model="place.district"
          class="m-2"
          placeholder="Quận/Huyện"
          size="large"
        >
          <el-option
            v-for="item in districtList"
            :key="item.Id"
            :label="item.Name"
            :value="item.Name"
          />
        </el-select>
      </el-form-item>
      <el-form-item label="Chọn Phường/Xã" prop="ward">
        <el-select
          v-model="place.ward"
          class="m-2"
          placeholder="Phường/Xã"
          size="large"
        >
          <el-option
            v-for="item in wardList"
            :key="item.Id"
            :label="item.Name"
            :value="item.Name"
          />
        </el-select>
      </el-form-item>

      <el-form-item label="Địa điểm cụ thể" prop="address_detail">
        <el-input
          v-model="place.address_detail"
          autosize
          type="textarea"
          placeholder="Địa điểm cụ thể"
        />
      </el-form-item>

      <el-form-item label="Mô tả" prop="description">
        <el-input
          v-model="place.description"
          autosize
          type="textarea"
          placeholder="Mô tả"
        />
      </el-form-item>

      <input id="update-images" type="file" multiple="multiple" />

      <br /><br />
      <el-form-item>
        <el-button type="primary" @click="onSubmit">Create</el-button>
        <el-button @click="onCancel">Cancel</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { getAddressDetail } from "@/api/data";
import { createPlace } from "@/api/place";

export default {
  data() {
    return {
      place: {
        name: null,
        province: null,
        district: null,
        ward: null,
        description: null,
        address_detail: null,
      },
      districtList: [],
      wardList: [],
      addressData: [],
    };
  },
  created() {
    this.fetchData();
  },
  methods: {
    fetchData() {
      getAddressDetail().then((response) => {
        this.addressData = response.data;
      });
    },
    onProvinceChange() {
      this.districtList = this.getListDistrict();
      this.wardList = [];
      this.place.district = null;
      this.place.ward = null;
    },
    getListDistrict() {
      const selectedProvince = this.place.province;
      const province = this.addressData.find(item => item.Name === selectedProvince);
      return province ? province.Districts : [];
    },
    listWard() {
      const selectedDistrict = this.place.district;
      const district = this.districtList.find(item => item.Name === selectedDistrict);
      return district ? district.Wards : [];
    },
    onSubmit() {
      const formData = new FormData();
      const listImages = document.querySelector("#update-images");
      const len = listImages.files.length;
      for (let i = 0; i < len; i++) {
        formData.append("images[" + i + "]", listImages.files[i]);
      }
      formData.append("name", this.place.name);
      formData.append("province", this.place.province);
      formData.append("district", this.place.district);
      formData.append("address_detail", this.place.address_detail);
      formData.append("description", this.place.description);
      formData.append("ward", this.place.ward);

      createPlace(formData).then((response) => {
        if (response.code === 0) {
          this.$notify({
            message: "Create success",
            type: "success",
          });
          this.$router.push("/place/listing");
        }
      });
    },
    onCancel() {
      this.$router.push("/place/listing");
    },
  },
};
</script>

<style scoped>
.line {
  text-align: center;
}
.m-2 {
  width: 500px;
}
a {
  text-decoration: none;
}
</style>
