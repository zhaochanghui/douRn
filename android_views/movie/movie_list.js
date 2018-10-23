import React, { Component } from 'react';
import {View,Text,Button,StyleSheet,Image}  from 'react-native';

class MovieList extends Component{
    constructor(props){
        super(props);
        this.state = {
            data:[],
            loaded:false,
        };
        this.fetchData = this.fetchData.bind(this);
    }

    fetchData()
    {

    }

    render(){
        return(
            <View style={{flex:1}}>
                <Text> movie list  </Text>
                <Button
                    onPress={() => this.props.navigate('detail', { user: 'Lucy' })}
                    title='go detail'
                />
            </View>
        );
    }
}


export default MovieList;