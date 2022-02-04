const path = require('path');
const fs = require('fs');
const arrFolders = fs.readdirSync('./src');
const extension = {};

// fs.readdirSync(arrFolders).forEach(file => {
//   extension[(path.extname(fileNames).substr(1)).toString][file.toString];
// });
// fs.readdirSync(arrFolders).forEach((file, index) => {
//   extension[arrFolders[index]][file.toString];
// });

fs.readdirSync(arrFolders).forEach(file => {
	
  extension[(path.extname(fileNames).substr(1)).toString][file.toString];
});

// modules.exports = extension;