import Vue from 'vue'
Vue.filter('formatDateTime', function (value) {
  if (!value || value == '') return ''
  var nmBulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
  if (value.indexOf('T') > -1) {
    var date = new Date(value)
    var tanggal = date.getDate();
    var bulan = date.getMonth() + 1;
    var jam = date.getHours().toString().padStart(2, '0')
    var menit = date.getMinutes().toString().padStart(2, '0')
    // return nmBulan[bulan];
    return tanggal + " " + nmBulan[bulan] + " " + date.getFullYear() + " " + jam + ":" + menit;
  }
  var tanggal = parseInt(value.toString().substring(8, 10));
  var bulan = parseInt(value.toString().substring(5, 7));
  var jam = value.toString().substring(11, 13).padStart(2, '0')
  var menit = value.toString().substring(14, 16).padStart(2, '0')
  // return nmBulan[bulan];
  return tanggal + " " + nmBulan[bulan] + " " + value.toString().substring(0, 4) + " " + jam + ":" + menit;
});
Vue.filter('formatDate', function (value) {
  if (!value || value == '') return ''
  var nmBulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
  if (value.indexOf('T') > -1) {
    var date = new Date(value)
    var tanggal = date.getDate();
    var bulan = date.getMonth() + 1;
    // return nmBulan[bulan];
    return tanggal + " " + nmBulan[bulan] + " " + date.getFullYear()
  }
  var tanggal = parseInt(value.toString().substring(8, 10));
  var bulan = parseInt(value.toString().substring(5, 7));
  var jam = value.toString().substring(11, 13).padStart(2, '0')
  var menit = value.toString().substring(14, 16).padStart(2, '0')
  // return nmBulan[bulan];
  return tanggal + " " + nmBulan[bulan] + " " + value.toString().substring(0, 4)
});
Vue.filter('formatDateTime', function (value) {
  if (!value || value == '') return ''
  var nmBulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
  if (value.indexOf('T') > -1) {
    var date = new Date(value)
    var tanggal = date.getDate();
    var bulan = date.getMonth() + 1;
    // return nmBulan[bulan];
    return tanggal + " " + nmBulan[bulan] + " " + date.getFullYear()
  }
  var tanggal = parseInt(value.toString().substring(8, 10));
  var bulan = parseInt(value.toString().substring(5, 7));
  var jam = value.toString().substring(11, 13).padStart(2, '0')
  var menit = value.toString().substring(14, 16).padStart(2, '0')
  // return nmBulan[bulan];
  return tanggal + " " + nmBulan[bulan] + " " + value.toString().substring(0, 4) + " " + jam + ":" + menit
});
Vue.filter('remainingTime', function(obj) {
  let strs = []
  if (obj.days) {
    strs.push(obj.days + ' hari')
  }
  if (obj.hours) {
    strs.push(obj.hours + ' jam')
  }
  if (obj.minutes) {
    strs.push(obj.minutes + ' menit')
  }
  if (obj.seconds) {
    strs.push(obj.seconds + ' detik')
  }
  return strs.join(', ')
});
Vue.filter('remainingTimeFormat', function(obj) {
  let strs = []
  if (obj.days) {
    strs.push(obj.days + ' hari')
  }
  if (obj.hours || obj.minutes || obj.seconds) {
    strs.push((obj.hours+'').padStart(2, '0') + ':' + (obj.minutes+'').padStart(2, '0') + ':' + (obj.seconds+'').padStart(2, '0'))
  }
  return strs.join(' ')
});
Vue.filter('timeElapsed', function(t1, t2) {
  if (typeof t1 == 'string') {
    t1 = new Date(t1)
  }
  if (typeof t2 == 'string') {
    t2 = new Date(t2)
  }
  if (!t2) {
    t2 = new Date
  }
  const total = Math.abs(t1.getTime() - t2.getTime())
  const seconds = Math.floor( (total/1000) % 60 );
  const minutes = Math.floor( (total/1000/60) % 60 );
  const hours = Math.floor( (total/(1000*60*60)) % 24 );
  const days = Math.floor( total/(1000*60*60*24) );

  return {
    total,
    days,
    hours,
    minutes,
    seconds
  };
});
