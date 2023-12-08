#Set up Cluster Guide
## Step 1: Create Natnetwork for VirtualBox
1. Open command prompt with admin rights (right click and Run as administrator) in your Oracle VirtualBox installation Location: <br>
  Change to default installation location: ```cd C:\Program Files\Oracle\VirtualBox```
2. Run command to add natnetwork: <br>
  ```VBoxManage natnetwork add --netname natnet1 --network "192.168.10.0/24" --enable```
3. Enable DHCP: <br>
  ```VBoxManage natnetwork modify --netname natnet1 --dhcp on```
4. Deploy script using Deployment server VM: <br>
  ```.\deploy_cluster.sh```
