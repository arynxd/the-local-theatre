rm -rf out/
mkdir out/
cd frontend/
npm run build
cp -R build/* ../out
cd ../backend
cp -R src/* ../out
echo 'Done! Transfer the files now.'
