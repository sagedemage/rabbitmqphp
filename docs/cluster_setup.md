# Set up Cluster Guide

## Step 1: Create Natnetwork for VirtualBox
1. Run command to add natnetwork: <br>
  ```VBoxManage natnetwork add --netname natnet1 --network "192.168.10.0/24" --enable```
2. Enable DHCP: <br>
  ```VBoxManage natnetwork modify --netname natnet1 --dhcp on```
3. Deploy script using Deployment server VM: <br>
  ```./deploy_cluster.sh```
