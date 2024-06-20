<template>
  <div class="app-container">
    <el-table
      v-loading="listLoading"
      :data="list"
      element-loading-text="Loading"
      border
      fit
      highlight-current-row
    >
      <el-table-column align="center" label="ID" width="95">
        <template slot-scope="scope">
          {{ scope.$index + 1 }}
        </template>
      </el-table-column>
      <el-table-column align="center" label="Name" width="200">
        <template slot-scope="scope">
          {{ scope.row.name }}
        </template>
      </el-table-column>
      <el-table-column align="center" label="Quận/Huyện" width="200">
        <template slot-scope="scope">
          {{ scope.row.district }}
        </template>
      </el-table-column>
      <el-table-column align="center" label="Phường/Xã" width="200">
        <template slot-scope="scope">
          {{ scope.row.ward }}
        </template>
      </el-table-column>
      <el-table-column align="center" label="Địa điểm cụ thể" width="200">
        <template slot-scope="scope">
          {{ scope.row.address_detail }}
        </template>
      </el-table-column>
      <el-table-column
        label="Actions"
        align="center"
        width="300"
        class-name="small-padding fixed-width"
      >
        <template slot-scope="scope">
          <el-button
            type="primary"
            size="mini"
            @click="handleUpdate(scope.$index)"
          >
            Edit
          </el-button>
          <el-button
            size="mini"
            type="danger"
            @click="handleDelete(scope.$index)"
          >
            Delete
          </el-button>
        </template>
      </el-table-column>
    </el-table>

    <el-dialog title="Edit Place" :visible.sync="dialogFormVisible">
      <el-form
        ref="dataForm"
        :model="place"
        label-position="left"
        style="width: 400px; margin-left: 50px"
      >
        <el-form-item label="Tên địa điểm" prop="name">
          <el-input v-model="place.name" style="width: 400px; margin-left: 5px" />
        </el-form-item>
        <el-form-item label="Chọn Tỉnh/Thành phố" prop="province">
          <el-select
            v-model="place.province"
            placeholder="Tỉnh/Thành phố"
            style="width: 400px"
            @change="handleProvinceChange"
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
            placeholder="Quận/Huyện"
            style="width: 400px"
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
            placeholder="Phường/Xã"
            style="width: 400px"
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
            style="width: 400px"
          />
        </el-form-item>
        <el-form-item label="Mô tả" prop="description">
          <el-input
            v-model="place.description"
            autosize
            type="textarea"
            placeholder="Mô tả"
            style="width: 400px"
          />
        </el-form-item>
        <input
          id="update-images"
          accept="image/*"
          type="file"
          @change="previewFiles($event)"
          multiple
        />
        <ul>
          <li v-for="(image, index) in list_images" :key="index">
            <img style="width: 200px" :src="image" alt="picture text" />
          </li>
        </ul>
        <div class="preview">
          <img id="update-images1" />
        </div>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">Cancel</el-button>
        <el-button
          v-if="!dialogCreate"
          type="primary"
          @click="updatePlace(place)"
        >
          Update
        </el-button>
        <el-button v-if="dialogCreate" type="primary" @click="createCategory(category)">
          Create
        </el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import { getAddressDetail } from "@/api/data";
import { getListPlace, updatePlace, deletePlace } from "@/api/place";

export default {
  data() {
    return {
      dialogFormVisible: false,
      dialogCreate: false,
      list: [],
      place: {
        name: "",
        province: "",
        district: "",
        ward: "",
        address_detail: "",
        description: "",
        images: []
      },
      listLoading: true,
      addressData: [],
      districtList: [],
      wardList: [],
      list_images: [],
      formData: null,
      isUpdateImage: false,
      index: null,
    };
  },
  created() {
    this.fetchData();
  },
  methods: {
    async fetchData() {
      this.listLoading = true;
      this.formData = new FormData();
      await getListPlace().then((response) => {
        this.list = response.data;
        if (this.list.length > 0) {
          this.place = { ...this.list[0], images: [...this.list[0].images] };
          this.list_images = [...this.list[0].images];
        } else {
          this.place = {
            name: "",
            province: "",
            district: "",
            ward: "",
            address_detail: "",
            description: "",
            images: []
          };
        }
        this.listLoading = false;
      });
      await getAddressDetail().then((response) => {
        this.addressData = response.data;
      });
    },
    previewFiles(event) {
      this.isUpdateImage = true;
      this.list_images = [];
      let len = event.target.files.length;
      if (len > 0) {
        for (let i = 0; i < len; i++) {
          let src = URL.createObjectURL(event.target.files[i]);
          this.list_images.push(src);
          this.formData.append("images[" + i + "]", event.target.files[i]);
        }
      }
      this.formData.append("name", this.place.name);
      this.formData.append("province", this.place.province);
      this.formData.append("district", this.place.district);
      this.formData.append("address_detail", this.place.address_detail);
      this.formData.append("description", this.place.description);
      this.formData.append("ward", this.place.ward);
      this.formData.append("id", this.place.id);
      console.log(this.formData);
    },
    updatePlace(place) {
      if (this.isUpdateImage) {
        updatePlace(this.formData).then((response) => {
          if (response.code === 0) {
            this.$notify({
              message: "Update success",
              type: "success",
            });
            this.dialogFormVisible = false;
          }
          this.list[this.index].images = response.data.images;
        });
      } else {
        updatePlace(place).then((response) => {
          if (response.code === 0) {
            this.$notify({
              message: "Update success",
              type: "success",
            });
            this.dialogFormVisible = false;
          }
        });
      }
    },
    handleUpdate(index) {
      this.place = { ...this.list[index], images: [...this.list[index].images] };
      this.list_images = [...this.list[index].images];
      this.dialogFormVisible = true;
      this.dialogCreate = false;
      this.index = index;
    },
    handleDelete(index) {
      deletePlace({ id: this.list[index].id }).then((response) => {
        if (response.code === 0) {
          this.$notify({
            message: "Delete success",
            type: "success",
          });
          this.fetchData();
        }
      });
    },
    listDistrict() {
      for (let i = 0; i < this.addressData.length; i++) {
        if (this.addressData[i].Name == this.place.province) {
          this.districtList = this.addressData[i].Districts;
          return this.districtList;
        }
      }
      return [];
    },
    listWard() {
      for (let i = 0; i < this.districtList.length; i++) {
        if (this.districtList[i].Name == this.place.district) {
          this.wardList = this.districtList[i].Wards;
          return this.wardList;
        }
      }
      return [];
    },
    handleProvinceChange() {
      this.place.district = "";
      this.place.ward = "";
    },
  },
};
</script>

<style lang="scss" scoped>
.user-avatar {
  cursor: pointer;
  width: 60px;
  height: 60px;
  border-radius: 10px;
}
.table {
  text-align: center;
}
</style>
