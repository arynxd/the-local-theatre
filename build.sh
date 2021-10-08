rm -rf out/
mkdir out/
cd frontend/
yarn build
cp -R build/* ../out
cd ../backend
cp -R src/* ../out
echo 'Done! Transfer the files now.'
