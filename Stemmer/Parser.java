import java.sql.Statement;
import java.io.File;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InputStream;
import java.io.PrintWriter;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.Scanner;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author tanveer
 */
public class Parser {
    public static void main(String args[]) throws FileNotFoundException, IOException, ClassNotFoundException, InstantiationException, IllegalAccessException, SQLException
    {
        Class.forName("com.mysql.jdbc.Driver").newInstance();
        Connection connection = DriverManager.getConnection("jdbc:mysql://localhost/prefix_suffix_stemmer?useUnicode=yes&characterEncoding=UTF-8", "root", "cse@254");
        Statement statement = connection.createStatement();
        File words = new File("roots.txt");
        File suffix = new File("suffix_list.txt");
        //File rootLd5 = new File("RootsLD5.txt");
        
        //PrintWriter writer = new PrintWriter(rootLd5);
        Scanner wordsc = new Scanner(words);
        Scanner suffixSc = new Scanner(suffix);
        ArrayList<String> suffixList = new ArrayList<>();
        while(suffixSc.hasNextLine())
        {
            suffixList.add(suffixSc.nextLine().trim());
        }
        int count = 0;
        while(wordsc.hasNextLine())
        {
            String word = wordsc.nextLine().trim();
            String suffixedWords = "";
            System.out.println(++count);
            if(count<78300)
                continue;
            statement.executeUpdate("insert into base_words values("+count+",'"+word+"');");
            for(int i = 0; i <suffixList.size() ; i++)
            {
                    if(i!= suffixList.size()-1)
                    {
                        suffixedWords+= "('"+word+suffixList.get(i).trim()+"',"+count+"),";
                    }
                    else
                    {
                        suffixedWords+= "('"+suffixList.get(i).trim()+"',"+count+")";
                    }
                    
                
            }
            try
            {
            statement.executeUpdate("insert ignore into prefix_words values"+suffixedWords);
            }catch(Exception e)
            {
                e.printStackTrace();
            }
            
            
        }
        suffixSc.close();
        wordsc.close();
        //writer.close();
    }
    
}
