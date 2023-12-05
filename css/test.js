fetch('http://13.79.115.108/api/PasswordGenerator/generate')
.then(response => response.text())
.then(password => {
console.log('Generated password:', password);
});